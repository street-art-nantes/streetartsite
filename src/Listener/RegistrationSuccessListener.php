<?php

namespace App\Listener;

use App\Service\Mailer;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\FOSUserEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class RegistrationSuccessListener implements EventSubscriberInterface
{
    private $mailer;
    private $router;

    public function __construct(Mailer $mailer,
                                UrlGeneratorInterface $router)
    {
        $this->mailer = $mailer;
        $this->router = $router;
    }

    public static function getSubscribedEvents()
    {
        return [
            FOSUserEvents::REGISTRATION_COMPLETED => [
                ['onRegistrationSuccess',  -10],
            ],
        ];
    }

    public function onRegistrationSuccess(FormEvent $event)
    {
        /** @var $user \FOS\UserBundle\Model\UserInterface */
        $user = $event->getForm()->getData();

//        die('plop');

        // send details out to the user
        $this->mailer->sendWelcomeEmailMessage($user);
    }
}
