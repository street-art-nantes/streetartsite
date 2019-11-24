<?php

namespace App\EventSubscriber;

use ApiPlatform\Core\EventListener\EventPriorities;
use ApiPlatform\Core\Util\RequestAttributesExtractor;
use App\Entity\Artwork;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Vich\UploaderBundle\Storage\StorageInterface;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;

/**
 * Class ArtworkEventSubscriber.
 */
final class ArtworkEventSubscriber implements EventSubscriberInterface
{
    /**
     * @var StorageInterface
     */
    private $storage;

    /**
     * @var CacheManager
     */
    private $imagineCacheManager;

    /**
     * @var UploaderHelper
     */
    private $uploaderHelper;

    /**
     * DocumentEventSubscriber constructor.
     *
     * @param StorageInterface $storage
     * @param CacheManager     $imagineCacheManager
     * @param UploaderHelper   $uploaderHelper
     */
    public function __construct(StorageInterface $storage, CacheManager $imagineCacheManager, UploaderHelper $uploaderHelper)
    {
        $this->storage = $storage;
        $this->uploaderHelper = $uploaderHelper;
        $this->imagineCacheManager = $imagineCacheManager;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::VIEW => ['onPreSerialize', EventPriorities::PRE_SERIALIZE],
        ];
    }

    /**
     * @param ViewEvent $event
     */
    public function onPreSerialize(ViewEvent $event): void
    {
        $controllerResult = $event->getControllerResult();
        $request = $event->getRequest();

        if ($controllerResult instanceof Response || !$request->attributes->getBoolean('_api_respond', true)) {
            return;
        }

        if (!($attributes = RequestAttributesExtractor::extractAttributes($request)) || !is_a($attributes['resource_class'], Artwork::class, true)) {
            return;
        }

        $artworkObjects = $controllerResult;

        if (!is_iterable($artworkObjects)) {
            $artworkObjects = [$artworkObjects];
        }

        foreach ($artworkObjects as $artworkObject) {
            if (!$artworkObject instanceof Artwork) {
                continue;
            }

            foreach ($artworkObject->getDocuments() as $document) {
                if ($document->getImageKitData()) {
                    continue;
                }

                $uri = $this->storage->resolveUri($document, 'imageFile');

                $document->setImageURISmall($this->imagineCacheManager->getBrowserPath($uri, 'thumb_smallmedium'));
                $document->setImageURIMedium($this->imagineCacheManager->getBrowserPath($uri, 'thumb_small'));
                $document->setImageURILarge($this->imagineCacheManager->getBrowserPath($uri, 'thumb_large'));
            }
        }
    }
}
