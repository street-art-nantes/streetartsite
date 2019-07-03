<?php

namespace App\Security\Provider;

use FOS\UserBundle\Model\UserManagerInterface;
use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;
use HWI\Bundle\OAuthBundle\Security\Core\User\FOSUBUserProvider as BaseFOSUBProvider;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Translation\TranslatorInterface;

class UserProvider extends BaseFOSUBProvider
{

    /**
     * @var UserPasswordEncoder
     */
    protected $encoder;

    /**
     * @var TokenStorage
     */
    protected $tokenStorage;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @var Session
     */
    private $session;

    /**
     * UserProvider constructor.
     * @param UserManagerInterface $userManager
     * @param array $properties
     * @param UserPasswordEncoder $encoder
     * @param TokenStorage $tokenStorage
     * @param TranslatorInterface $translator
     * @param Session $session
     */
    public function __construct(UserManagerInterface $userManager, array $properties, UserPasswordEncoder $encoder,
                                TokenStorage $tokenStorage, TranslatorInterface $translator, Session $session)
    {
        parent::__construct($userManager, $properties);
        $this->encoder = $encoder;
        $this->tokenStorage = $tokenStorage;
        $this->translator = $translator;
        $this->session = $session;
    }

    /**
     * {@inheritDoc}
     */
    public function connect(UserInterface $user, UserResponseInterface $response)
    {
        $property = $this->getProperty($response);
        $username = $response->getUsername();
        //on connect - get the access token and the user ID
        $service = $response->getResourceOwner()->getName();
        $setter = 'set'.ucfirst($service);
        $setter_id = $setter.'Id';
        $setter_token = $setter.'AccessToken';
        //we "disconnect" previously connected users
        if (null !== $previousUser = $this->userManager->findUserBy(array($property => $username))) {
            $previousUser->$setter_id(null);
            $previousUser->$setter_token(null);
            $this->userManager->updateUser($previousUser);
        }

        $user->$setter_id($username);
        $user->$setter_token($response->getAccessToken());
        $this->userManager->updateUser($user);

        // login user
        $token = new UsernamePasswordToken($user, null, 'main', $user->getRoles());
        $this->tokenStorage->setToken($token);
    }

    /**
     * {@inheritdoc}
     */
    public function loadUserByOAuthUserResponse(UserResponseInterface $response)
    {
        $username = $response->getUsername();
        $user = $this->userManager->findUserBy(array($this->getProperty($response) => $username));

        $service = $response->getResourceOwner()->getName();
        $setter = 'set'.ucfirst($service);
        $setter_id = $setter.'Id';
        $setter_token = $setter.'AccessToken';

        // if never logged with current provider search by email
        if (null === $user && $response->getEmail()) {
            $user = $this->userManager->findUserBy(array('email' => $response->getEmail()));
        }

        if (null === $user) {
            // create new user here if not found by id or email
            $user = $this->userManager->createUser();
            $user->$setter_id($username);
            $user->$setter_token($response->getAccessToken());
            $user->setUsername($response->getNickname());
            // Generate fake email if not in response (aka instagram)
            $user->setEmail($response->getEmail() ?: $username.'_'.uniqid().'@street-artwork.com');
            $password = $this->encoder->encodePassword($user, $username.'_'.uniqid());
            $user->setPassword($password);
            $user->setEnabled(true);
            $this->userManager->updateUser($user);
            $this->session->getFlashBag()->add('notice', $this->translator->trans(
                'user.flash.created',
                ['nickname' => $response->getNickname()]
            ));
        } else {
            $user->$setter_id($username);
            $user->$setter_token($response->getAccessToken());
            $this->session->getFlashBag()->add('notice', $this->translator->trans(
                'user.flash.loggedin',
                ['nickname' => $response->getNickname()]
            ));
        }

        return $user;
    }
}