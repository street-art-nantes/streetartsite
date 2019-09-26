<?php

namespace App\Listener;

use App\Entity\User;
use App\Service\Mailer;
use FOS\UserBundle\Event\FilterUserResponseEvent;
use FOS\UserBundle\FOSUserEvents;
use Nexy\Slack\Client;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class RegistrationSuccessListener implements EventSubscriberInterface
{
    /**
     * @var Mailer
     */
    private $mailer;

    /**
     * @var Client
     */
    private $slackClient;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * RegistrationSuccessListener constructor.
     *
     * @param Mailer          $mailer
     * @param Client          $slackClient
     * @param LoggerInterface $logger
     */
    public function __construct(Mailer $mailer, Client $slackClient, LoggerInterface $logger)
    {
        $this->mailer = $mailer;
        $this->slackClient = $slackClient;
        $this->logger = $logger;
    }

    /**
     * @return array
     */
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
        /** @var User $user */
        $user = $event->getUser();

        try {
            $message = $this->slackClient->createMessage();

            $message
                ->to('#new-users')
                ->from('Street Artwork bot')
                ->withIcon(':genie:')
                ->setText(sprintf('%s rejoint Street Artwork! Ville: %s Pays:%s', $user->getUsername(), $user->getCity(), $user->getCountry()))
            ;

            $this->slackClient->sendMessage($message);
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
        } catch (\Http\Client\Exception $e) {
            $this->logger->error($e->getMessage());
        }

        try {
            $this->mailer->sendWelcomeEmailMessage($user);
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
        }
    }
}
