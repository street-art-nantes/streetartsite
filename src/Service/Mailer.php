<?php

namespace App\Service;

use App\Entity\Artwork;
use FOS\UserBundle\Model\UserInterface;
use Liip\ImagineBundle\Service\FilterService;
use Swift_Mailer as BaseMailer;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\Asset\Packages as AssetPackages;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Translation\TranslatorInterface;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;

class Mailer
{
    /**
     * @var BaseMailer
     */
    private $baseMailer;

    /**
     * @var UrlGeneratorInterface
     */
    protected $router;

    /**
     * @var EngineInterface
     */
    private $templating;

    /**
     * @var AssetPackages
     */
    protected $assetPackages;

    /**
     * @var TranslatorInterface
     */
    protected $translator;

    /**
     * @var array
     */
    protected $parameters;

    /**
     * @var UploaderHelper
     */
    private $helper;

    /**
     * @var FilterService
     */
    private $filterService;

    /**
     * Mailer constructor.
     *
     * @param BaseMailer            $baseMailer
     * @param UrlGeneratorInterface $router
     * @param EngineInterface       $templating
     * @param AssetPackages         $assetPackages
     * @param TranslatorInterface   $translator
     * @param UploaderHelper        $helper
     * @param FilterService         $filterService
     * @param array                 $parameters
     */
    public function __construct(BaseMailer $baseMailer, UrlGeneratorInterface $router, EngineInterface $templating,
                                AssetPackages $assetPackages, TranslatorInterface $translator, UploaderHelper $helper,
                                FilterService $filterService, array $parameters)
    {
        $this->baseMailer = $baseMailer;
        $this->router = $router;
        $this->templating = $templating;
        $this->assetPackages = $assetPackages;
        $this->translator = $translator;
        $this->helper = $helper;
        $this->filterService = $filterService;
        $this->parameters = $parameters;
    }

    /**
     * @param UserInterface $user
     */
    public function sendWelcomeEmailMessage(UserInterface $user)
    {
        $template = 'email/welcome.twig';
        $urlAccount = $this->router->generate('public_profile', ['id' => $user->getId()], 0);
        $urlForm = $this->router->generate('app_artwork_new', [], 0);
        $urlMap = $this->router->generate('map');
        $urlLogo = $this->assetPackages->getUrl('assets/img/logo.png');
        $urlHeaderLogo = $this->assetPackages->getUrl('assets/img/email-logo.png');
        $rendered = $this->templating->render($template, [
            'user' => $user,
            'urlAccount' => $urlAccount,
            'urlForm' => $urlForm,
            'urlMap' => $urlMap,
            'urlLogo' => $urlLogo,
            'urlHeaderLogo' => $urlHeaderLogo,
        ]);
        $subject = $this->translator->trans('welcome.email.subject', ['%username%' => $user->getUsername()], 'FOSUserBundle');
        $this->sendEmailMessage($rendered,
            ['contact@street-artwork.com' => 'street-artwork.com'],
            [$user->getEmail() => $user->getUsername()],
            $user,
            $subject);
    }

    /**
     * @param Request       $request
     * @param UserInterface $user
     */
    public function sendSubmissionEmailMessage(Request $request, UserInterface $user)
    {
        $template = 'email/submission.twig';
        $urlForm = $this->router->generate('app_artwork_new', [], 0);
        $urlLogo = $this->assetPackages->getUrl('assets/img/logo.png');
        $urlHeaderLogo = $this->assetPackages->getUrl('assets/img/email-logo.png');
        $rendered = $this->templating->render($template, [
            'user' => $user,
            'urlForm' => $urlForm,
            'urlLogo' => $urlLogo,
            'urlHeaderLogo' => $urlHeaderLogo,
            'datas' => $request->request->all(),
        ]);
        $subject = $this->translator->trans('submission.subject', ['%username%' => $user->getUsername()], 'TransactionalEmail');
        $this->sendEmailMessage($rendered,
            ['contact@street-artwork.com' => 'street-artwork.com'],
            [$user->getEmail() => $user->getUsername()],
            $user,
            $subject);
    }

    /**
     * @param Artwork       $artwork
     * @param UserInterface $user
     */
    public function sendValidationEmailMessage(Artwork $artwork, UserInterface $user)
    {
        $template = 'email/validation.twig';
        $urlForm = $this->router->generate('app_artwork_new', [], 0);
        $urlArtwork = $this->router->generate('artwork', ['id' => $artwork->getPoi()->getId()], 0);
        $document = $artwork->getDocuments()->first();
        $urlImgArtwork = $this->filterService->getUrlOfFilteredImage($this->helper->asset($document, 'imageFile'), 'thumb_small');
        $urlHeaderLogo = $this->assetPackages->getUrl('assets/img/email-logo.png');
        $rendered = $this->templating->render($template, [
            'user' => $user,
            'urlForm' => $urlForm,
            'urlHeaderLogo' => $urlHeaderLogo,
            'artwork' => $artwork,
            'urlArtwork' => $urlArtwork,
            'imgArtwork' => $urlImgArtwork,
        ]);
        $subject = $this->translator->trans('validation.subject', ['%username%' => $user->getUsername()], 'TransactionalEmail');
        $this->sendEmailMessage($rendered,
            ['contact@street-artwork.com' => 'street-artwork.com'],
            [$user->getEmail() => $user->getUsername()],
            $user,
            $subject);
    }

    /**
     * @param UserInterface $user
     * @param array         $newBadges
     */
    public function sendNewBadgesEmailMessage(UserInterface $user, array $newBadges)
    {
        $template = 'email/new_badges.twig';
        $urlHeaderLogo = $this->assetPackages->getUrl('assets/img/email-logo.png');
        $urlLogo = $this->assetPackages->getUrl('assets/img/logo.png');
        $rendered = $this->templating->render($template, [
            'user' => $user,
            'urlLogo' => $urlLogo,
            'urlHeaderLogo' => $urlHeaderLogo,
            'newBadges' => $newBadges,
            'urlBadges' => $this->assetPackages->getUrl('assets/img/badges/'),
        ]);
        $subject = $this->translator->trans('badges.subject', ['%username%' => $user->getUsername()], 'TransactionalEmail');
        $this->sendEmailMessage($rendered,
            ['contact@street-artwork.com' => 'street-artwork.com'],
            [$user->getEmail() => $user->getUsername()],
            $user,
            $subject);
    }

    /**
     * @param string        $renderedTemplate
     * @param mixed         $fromEmail
     * @param mixed         $toEmail
     * @param UserInterface $user
     * @param string        $subject
     */
    protected function sendEmailMessage($renderedTemplate, $fromEmail, $toEmail, UserInterface $user, $subject)
    {
        $message = (new \Swift_Message())
            ->setSubject($subject)
            ->setFrom($fromEmail)
            ->setTo($toEmail)
            ->setBody($renderedTemplate, 'text/html');

        $this->baseMailer->send($message);
    }
}
