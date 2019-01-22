<?php

namespace App\Listener;

use App\Entity\Artwork;
use App\Entity\User;
use App\Service\Mailer;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;

class EasyAdminListener implements EventSubscriberInterface
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var Mailer
     */
    private $mailer;

    public function __construct(Mailer $mailer, LoggerInterface $logger)
    {
        $this->mailer = $mailer;
        $this->logger = $logger;
    }

    public static function getSubscribedEvents()
    {
        return [
            'easy_admin.post_update' => ['sendValidationEmail'],
        ];
    }

    public function sendValidationEmail(GenericEvent $event)
    {
        $entity = $event->getSubject();

        if (!($entity instanceof Artwork) || !($entity->getContributor() instanceof User)) {
            return;
        }

        try {
            $this->mailer->sendValidationEmailMessage($entity, $entity->getContributor());
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
        }
    }
}
