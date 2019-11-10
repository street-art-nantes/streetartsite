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
        return ['prePersist', 'preUpdate'];
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

    /**
     * @param LifecycleEventArgs $args
     */
    public function preUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        if (!$entity instanceof Artwork) {
            return;
        }

        $entity->setContributor($this->security->getUser());

        // necessary to force the update to see the change
        $manager = $args->getEntityManager();
        $meta = $manager->getClassMetadata(\get_class($entity));
        $manager->getUnitOfWork()->recomputeSingleEntityChangeSet($meta, $entity);
    }
}
