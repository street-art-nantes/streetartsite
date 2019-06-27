<?php

namespace App\Controller;

use Facebook\Exceptions\FacebookSDKException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\HttpFoundation\Response;

use App\Entity\User;
use Facebook\Facebook;

class FacebookController extends Controller
{
    private $app_id = 'xxx';
    private $app_secret = 'xxxx';

    /**
     * @Route("/facebook-login", name="facebook_login")
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @throws FacebookSDKException
     */
    public function loginAction(Request $request)
    {
        $helper = $this->getFacebookHelper();
        $permissions = ['email'];
        $login_url = $helper->getLoginUrl($this->generateUrl('facebook_login_check', array(), 0), $permissions);

        return $this->redirect($login_url);
    }

    /**
     * @Route("/facebook-login-check", name="facebook_login_check")
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @throws FacebookSDKException
     */
    public function loginCheckAction(Request $request)
    {
        $fb = $this->getFacebook();
        $helper = $this->getFacebookHelper($fb);

        try {
            $accessToken = $helper->getAccessToken();
        } catch(FacebookSDKException $e) {
            $error = $e->getMessage();
        }

        if (!isset($accessToken)) {
            if ($helper->getError()) {
                $error = 'error '.$helper->getErrorCode().' - '.$helper->getError().' - '.$helper->getErrorReason().' - '.$helper->getErrorDescription();
            }
            else {
                $error = 'Bad request';
            }
        }

        if (!isset($error)) {
            // User is logged in
            $oAuth2Client = $fb->getOAuth2Client();
            $tokenMetadata = $oAuth2Client->debugToken($accessToken);
            $facebook_user_id = $tokenMetadata->getUserId();

            $tokenMetadata->validateAppId($this->app_id);
            $tokenMetadata->validateUserId($facebook_user_id);
            $tokenMetadata->validateExpiration();

            if (!$accessToken->isLongLived()) {
                try {
                    $accessToken = $oAuth2Client->getLongLivedAccessToken($accessToken);
                } catch (FacebookSDKException $e) {
                    $error = "<p>Error getting long-lived access token: " . $helper->getMessage() . "</p>";
                }
            }
        }

        if (isset($error)) {
            return $this->render('pages/welcome.html.twig', array('error' => $error));
        } else {
            // check if user exists
            $em = $this->getDoctrine()->getManager();
            $user = $this->getDoctrine()->getRepository('App:User')->findOneBy(
                array(
                    'enabled' => true,
                    'facebookUserId' => $facebook_user_id,
                )
            );

            // if no, create new user
            if (empty($user)) {
                $user_infos = $this->getFacebookUserInfos($accessToken, $fb);

                if ($user_infos != null) {
                    // check if email address is already used
                    $check_email     = $this->getDoctrine()->getRepository('App:User')->findOneBy(['email' => $user_infos['email']]);
                    if (!empty($check_email)) {
                        $form_errors            = array();
                        $form_errors['email']   = true;

                        return $this->render('FOSUserBundle:Registration:register.html.twig', array(
                            'form_errors'           => $form_errors,
                        ));
                    }

//                    $role = $this->getDoctrine()->getRepository('EcoleUserBundle:UserRole')->findOneByName('ROLE_USER');

                    $user = new User();
                    $encoder = $this->get('security.encoder_factory')->getEncoder($user);
                    $password = $encoder->encodePassword($facebook_user_id.'_'.uniqid(), $user->getSalt());
                    $user->setPassword($password);

                    $user->setEmail($user_infos['email']);
                    $user->setCreated(new \DateTime());
                    $user->setFirstname($user_infos['firstname']);
                    $user->setLastName($user_infos['lastname']);
                    $user->setFacebookUserId($facebook_user_id);
//                    $user->addRole($role);

                    $em->persist($user);
                    $em->flush();
                }
            }

            if (!empty($user) && true === $user->getIsActive()) {
                // set last login date
                $user->setLastLogin(new \DateTime());
                $em->persist($user);
                $em->flush();

                // login user
                $token = new UsernamePasswordToken($user, null, 'main', $user->getRoles());
                $this->get('security.token_storage')->setToken($token);
                return $this->redirect($this->generateUrl('login'));
            }
            else {
                $error = 'Unable to find user';
                return $this->render('pages/welcome.html.twig', array('error' => $error));
            }
        }
    }

    /**
     * @return Facebook
     * @throws FacebookSDKException
     */
    private function getFacebook()
    {
        $fb = new Facebook(
            array(
                'app_id' => $this->app_id,
                'app_secret' => $this->app_secret,
                'default_graph_version' => 'v3.3',
            )
        );
        return $fb;
    }

    /**
     * @param null $fb
     * @return \Facebook\Helpers\FacebookRedirectLoginHelper
     * @throws FacebookSDKException
     */
    private function getFacebookHelper($fb = null)
    {
        if ($fb === null) {
            $fb = $this->getFacebook();
        }
        $helper = $fb->getRedirectLoginHelper();
        return $helper;
    }

    /**
     * @param $accessToken
     * @param Facebook $fb
     * @return array|null
     */
    private function getFacebookUserInfos($accessToken, $fb)
    {
        try {
            $response = $fb->get('/me?fields=email,first_name,last_name,gender', $accessToken);
            $graphObject = $response->getGraphObject();
        } catch(FacebookSDKException $e) {
            var_dump($e);
            return null;
        }

        try {
            $me = $response->getGraphUser();
        } catch(FacebookSDKException $e) {
            var_dump($e);
            return null;
        }
        return array(
            'firstname' => $me->getFirstName(),
            'lastname' => $me->getLastName(),
            'email' => $me->getEmail(),
            'gender' => $me->getGender(),
        );
    }
}