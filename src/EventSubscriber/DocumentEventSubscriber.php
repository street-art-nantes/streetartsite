<?php

namespace App\EventSubscriber;

use ApiPlatform\Core\EventListener\EventPriorities;
use ApiPlatform\Core\Util\RequestAttributesExtractor;
use App\Entity\Document;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Vich\UploaderBundle\Storage\StorageInterface;

final class DocumentEventSubscriber implements EventSubscriberInterface
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
     * DocumentEventSubscriber constructor.
     *
     * @param StorageInterface $storage
     * @param CacheManager     $imagineCacheManager
     */
    public function __construct(StorageInterface $storage, CacheManager $imagineCacheManager)
    {
        $this->storage = $storage;
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

        if (!($attributes = RequestAttributesExtractor::extractAttributes($request)) || !is_a($attributes['resource_class'], Document::class, true)) {
            return;
        }

        $mediaObjects = $controllerResult;

        if (!is_iterable($mediaObjects)) {
            $mediaObjects = [$mediaObjects];
        }

        foreach ($mediaObjects as $mediaObject) {
            if (!$mediaObject instanceof Document) {
                continue;
            }

            if ($mediaObject->getImageKitData()) {
                continue;
            }

            $uri = $this->storage->resolveUri($mediaObject, 'file');
            $mediaObject->setImageURISmall($this->imagineCacheManager->getBrowserPath($uri, 'thumb_smallmedium'));
            $mediaObject->setImageURIMedium($this->imagineCacheManager->getBrowserPath($uri, 'thumb_small'));
            $mediaObject->setImageURILarge($this->imagineCacheManager->getBrowserPath($uri, 'thumb_small'));
        }
    }
}
