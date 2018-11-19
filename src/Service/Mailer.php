<?php

namespace App\Service;

use FOS\UserBundle\Model\UserInterface;
use Swift_Mailer as BaseMailer;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\Asset\Packages as AssetPackages;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Translation\TranslatorInterface;

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
     * Mailer constructor.
     *
     * @param BaseMailer            $baseMailer
     * @param UrlGeneratorInterface $router
     * @param EngineInterface       $templating
     * @param AssetPackages         $assetPackages
     * @param TranslatorInterface   $translator
     * @param array                 $parameters
     */
    public function __construct(BaseMailer $baseMailer, UrlGeneratorInterface $router, EngineInterface $templating,
                                AssetPackages $assetPackages, TranslatorInterface $translator, array $parameters)
    {
        $this->baseMailer = $baseMailer;
        $this->router = $router;
        $this->templating = $templating;
        $this->assetPackages = $assetPackages;
        $this->translator = $translator;
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
        $urlLogo = $this->assetPackages->getUrl('assets/img/logo.png');
        $rendered = $this->templating->render($template, [
            'user' => $user,
            'urlAccount' => $urlAccount,
            'urlForm' => $urlForm,
            'urlLogo' => $urlLogo,
        ]);
        $this->sendEmailMessage($rendered,
            'contact@street-artwork.com',
            $user->getEmail(),
            $user);
    }

    /**
     * @param string $renderedTemplate
     * @param string $fromEmail
     * @param string $toEmail
     * @param UserInterface $user
     */
    protected function sendEmailMessage($renderedTemplate, $fromEmail, $toEmail, UserInterface $user)
    {
        $message = (new \Swift_Message())
            ->setSubject($this->translator->trans('welcome.email.subject', ['%username%' => $user->getUsername()], 'FOSUserBundle'))
            ->setFrom($fromEmail)
            ->setTo($toEmail)
            ->setBody($renderedTemplate, 'text/html');

        $this->baseMailer->send($message);
    }
}
