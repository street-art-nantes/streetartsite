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

    public function onRegistrationSuccess(FilterUserResponseEvent $event)
    {
        /** @var $user \FOS\UserBundle\Model\UserInterface */
        $user = $event->getUser();

//        die('plop');

        // send details out to the user
        $this->mailer->sendWelcomeEmailMessage($user);
    }
}
