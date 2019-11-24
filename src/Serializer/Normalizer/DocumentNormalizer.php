<?php

namespace App\Serializer\Normalizer;

use App\Entity\Document;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;

/**
 * Class DocumentNormalizer.
 */
class DocumentNormalizer implements NormalizerInterface, CacheableSupportsMethodInterface
{
    /**
     * @var ObjectNormalizer
     */
    private $normalizer;

    /**
     * @var UploaderHelper
     */
    private $uploaderHelper;

    /**
     * @var CacheManager
     */
    private $imagineCacheManager;

    /**
     * ArtworkNormalizer constructor.
     *
     * @param ObjectNormalizer $normalizer
     * @param UploaderHelper   $uploaderHelper
     * @param CacheManager     $imagineCacheManager
     */
    public function __construct(ObjectNormalizer $normalizer, UploaderHelper $uploaderHelper, CacheManager $imagineCacheManager)
    {
        $this->normalizer = $normalizer;
        $this->imagineCacheManager = $imagineCacheManager;
        $this->uploaderHelper = $uploaderHelper;
    }

    /**
     * @param mixed $object
     * @param null  $format
     * @param array $context
     *
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     *
     * @return array
     */
    public function normalize($object, $format = null, array $context = []): array
    {
        $document = $this->normalizer->normalize($object, $format, $context);
//        // TODO get class instead array
//        if ($document['imageKitData']) {
//            return $document;
//        }
//
//        $imageFile = $this->uploaderHelper->asset($document, 'imageFile');
//
//        $document['imageURILarge'] = $this->imagineCacheManager->getBrowserPath($imageFile, 'thumb_small');
//        $document['imageURIMedium'] = $this->imagineCacheManager->getBrowserPath($imageFile, 'thumb_small');
//        $document['imageURISmall'] = $this->imagineCacheManager->getBrowserPath($imageFile, 'thumb_smallmedium');

        return $document;
    }

    /**
     * @param mixed $data
     * @param null  $format
     *
     * @return bool
     */
    public function supportsNormalization($data, $format = null): bool
    {
        return $data instanceof Document;
    }

    /**
     * @return bool
     */
    public function hasCacheableSupportsMethod(): bool
    {
        return true;
    }
}
