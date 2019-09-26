<?php

namespace App\Twig;

use App\Entity\Document;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;

class AppExtension extends AbstractExtension
{
    /**
     * @var UploaderHelper
     */
    private $uploaderHelper;

    /**
     * @var CacheManager
     */
    private $imagineCacheManager;

    public function __construct(UploaderHelper $uploaderHelper, CacheManager $imagineCacheManager)
    {
        $this->uploaderHelper = $uploaderHelper;
        $this->imagineCacheManager = $imagineCacheManager;
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('getDocumentImage', [$this, 'getDocumentImage']),
        ];
    }

    public function getDocumentImage(Document $document, $filter = null)
    {
        if ($document->getImageURI()) {
            return $document->getImageURI();
        }

        $imageFile = $this->uploaderHelper->asset($document, 'imageFile');

        if ($filter) {
            $imageFile = $this->imagineCacheManager->getBrowserPath($imageFile, $filter);
        }

        return $imageFile;
    }
}
