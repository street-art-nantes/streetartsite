<?php

namespace App\Service;

use App\Entity\Artwork;
use App\Entity\Author;
use App\Entity\User;
use Liip\ImagineBundle\Service\FilterService;
use Swift_Mailer as BaseMailer;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\Asset\Packages as AssetPackages;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Translation\Translator;
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
     * @var Translator
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
     * @param Translator            $translator
     * @param UploaderHelper        $helper
     * @param FilterService         $filterService
     * @param array                 $parameters
     */
    public function __construct(BaseMailer $baseMailer, UrlGeneratorInterface $router, EngineInterface $templating,
                                AssetPackages $assetPackages, Translator $translator, UploaderHelper $helper,
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
     * @param User $user
     */
    public function sendWelcomeEmailMessage(User $user)
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
     * @param Request $request
     * @param User    $user
     */
    public function sendSubmissionEmailMessage(Request $request, User $user)
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
     * @param Request $request
     * @param User    $user
     */
    public function sendArtistSubmissionEmailMessage(Request $request, User $user)
    {
        $template = 'email/artist_submission.twig';
        $urlForm = $this->router->generate('app_artist_new', [], 0);
        $urlLogo = $this->assetPackages->getUrl('assets/img/logo.png');
        $urlHeaderLogo = $this->assetPackages->getUrl('assets/img/email-logo.png');
        $rendered = $this->templating->render($template, [
            'user' => $user,
            'urlForm' => $urlForm,
            'urlLogo' => $urlLogo,
            'urlHeaderLogo' => $urlHeaderLogo,
            'datas' => $request->request->all(),
        ]);
        $subject = $this->translator->trans('artist_submission.subject', ['%username%' => $user->getUsername()], 'TransactionalEmail');
        $this->sendEmailMessage($rendered,
            ['contact@street-artwork.com' => 'street-artwork.com'],
            [$user->getEmail() => $user->getUsername()],
            $user,
            $subject);
    }

    /**
     * @param Artwork $artwork
     * @param User    $user
     */
    public function sendValidationEmailMessage(Artwork $artwork, User $user)
    {
        if ($artwork->isEnabled()) {
            $language = $user->getLanguage();
            $this->translator->setLocale($language);
            $template = 'email/validation.twig';
            $urlForm = $this->router->generate('app_artwork_new', ['_locale' => $language], 0);
            $urlArtwork = $this->router->generate('artwork', ['id' => $artwork->getPoi()->getId(), '_locale' => $language], 0);
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
                '_locale' => $language,
            ]);
            $subject = $this->translator->trans('validation.subject', ['%username%' => $user->getUsername()], 'TransactionalEmail');
            $this->sendEmailMessage($rendered,
                ['contact@street-artwork.com' => 'street-artwork.com'],
                [$user->getEmail() => $user->getUsername()],
                $user,
                $subject);
        }
    }

    /**
     * @param Author $artist
     * @param User   $user
     */
    public function sendArtistValidationEmailMessage(Author $artist, User $user)
    {
        if ($artist->isEnabled()) {
            $language = $user->getLanguage();
            $this->translator->setLocale($language);
            $template = 'email/artist_validation.twig';
            $urlForm = $this->router->generate('app_artist_new', ['_locale' => $language], 0);
            $urlArtist = $this->router->generate('artist_profile', ['id' => $artist->getId(), '_locale' => $language], 0);
            $urlImgArtist = '';
            if ($artist->getAvatarName()) {
                $urlImgArtist = $this->filterService->getUrlOfFilteredImage($this->helper->asset($artist, 'avatarFile'), 'thumb_small');
            }
            $urlHeaderLogo = $this->assetPackages->getUrl('assets/img/email-logo.png');
            $rendered = $this->templating->render($template, [
                'user' => $user,
                'urlForm' => $urlForm,
                'urlHeaderLogo' => $urlHeaderLogo,
                'artist' => $artist,
                'urlArtist' => $urlArtist,
                'imgArtist' => $urlImgArtist,
            ]);
            $subject = $this->translator->trans('artist_validation.subject', ['%username%' => $user->getUsername()], 'TransactionalEmail');
            $this->sendEmailMessage($rendered,
                ['contact@street-artwork.com' => 'street-artwork.com'],
                [$user->getEmail() => $user->getUsername()],
                $user,
                $subject);
        }
    }

    /**
     * @param string $renderedTemplate
     * @param mixed  $fromEmail
     * @param mixed  $toEmail
     * @param User   $user
     * @param string $subject
     */
    protected function sendEmailMessage($renderedTemplate, $fromEmail, $toEmail, User $user, $subject)
    {
        $message = (new \Swift_Message())
            ->setSubject($subject)
            ->setFrom($fromEmail)
            ->setTo($toEmail)
            ->setBody($renderedTemplate, 'text/html');

        $this->baseMailer->send($message);
    }
}
