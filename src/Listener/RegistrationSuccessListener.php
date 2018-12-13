<?php

namespace App\Listener;

use App\Service\Mailer;
use FOS\UserBundle\Event\FilterUserResponseEvent;
use FOS\UserBundle\FOSUserEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class RegistrationSuccessListener implements EventSubscriberInterface
{
    private $mailer;

    public function __construct(Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    public static function getSubscribedEvents()
    {
        return [
            FOSUserEvents::REGISTRATION_COMPLETED => [
                ['onRegistrationSuccess',  -10],
            ],
        ];
    }

    /**
     * @param FilterUserResponseEvent $event
     */
    public function onRegistrationSuccess(FilterUserResponseEvent $event)
    {
        $user = $event->getUser();

        $this->mailer->sendWelcomeEmailMessage($user);
    }
}
