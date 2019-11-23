<?php

namespace App\Listener\User;

use App\Entity\User;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;

/**
 * Class JWTCreatedListener.
 */
class JWTCreatedListener
{
    /**
     * @param JWTCreatedEvent $event
     */
    public function onJWTCreated(JWTCreatedEvent $event)
    {
        $user = $event->getUser();
        $data = $event->getData();

        if (!$user instanceof User) {
            return;
        }

        $data['id'] = $user->getId();
        $event->setData($data);
    }
}
