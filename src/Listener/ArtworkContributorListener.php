<?php

namespace App\Listener;

use App\Entity\Artwork;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\Security\Core\Security;

/**
 * Class ArtworkContributorListener.
 */
class ArtworkContributorListener implements EventSubscriber
{
    /**
     * @var Security
     */
    private $security;

    /**
     * ArtworkContributorListener constructor.
     *
     * @param Security $security
     */
    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    /**
     * @return array
     */
    public function getSubscribedEvents()
    {
        return ['prePersist'];
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        if (!$entity instanceof Artwork) {
            return;
        }

        $entity->setContributor($this->security->getUser());
    }
}
