<?php

namespace App\Manager;

use App\Entity\Artwork;
use App\Entity\Document;
use App\Entity\Poi;
use Doctrine\ORM\EntityManagerInterface;
use Liip\ImagineBundle\Service\FilterService;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Class PoiManager.
 */
class PoiManager
{
    /**
     * @var EntityManagerInterface
     */
    private $manager;

    /**
     * @var UploaderHelper
     */
    private $helper;

    /**
     * @var FilterService
     */
    private $filterService;

    /**
     * @var UrlGeneratorInterface
     */
    private $router;

    /**
     * PoiManager constructor.
     *
     * @param EntityManagerInterface $manager
     * @param UploaderHelper         $helper
     * @param FilterService          $filterService
     * @param UrlGeneratorInterface          $router
     */
    public function __construct(EntityManagerInterface $manager, UploaderHelper $helper, FilterService $filterService,
                                UrlGeneratorInterface $router)
    {
        $this->manager = $manager;
        $this->helper = $helper;
        $this->filterService = $filterService;
        $this->router = $router;
    }

    /**
     * @param array $data
     */
    public function create(array $data)
    {
        $artwork = new Artwork();
        $poi = new Poi();
        $document = new Document();

        $poi->setCity($data['city']);
        $poi->setAddress($data['address']);
        $poi->setCountry($data['country']);
        $poi->setLatitude($data['lat']);
        $poi->setLongitude($data['lng']);
        $poi->setHighlight($data['highlight']);
        $this->manager->persist($poi);

        $artwork->setCreatedAt(new \DateTime($data['created_date']));
        $artwork->setEndedAt(new \DateTime($data['created_date']));
        $artwork->setUpdatedAt(new \DateTime($data['created_date']));
        $artwork->setEnabled(true);
        $artwork->setTitle($data['title']);
        $artwork->setType($data['type']);
        $artwork->setPoi($poi);
        $this->manager->persist($artwork);

        $document->setImageName($data['file']);
        $document->setArtwork($artwork);
        $document->setUpdatedAt(new \DateTime($data['created_date']));
        $this->manager->persist($document);

        $this->manager->flush();
    }

    /**
     * Temporary function for beta version.
     *
     * @param array $pois
     *
     * @return string
     */
    public function convertPoisForMap(array $pois)
    {
        $convertedPois = [];
        /** @var Poi $poi */
        foreach ($pois as $poi) {
            /** @var Artwork $artwork */
            $artwork = $poi->getArtworks()->first();
            /** @var Document $document */
            $document = $artwork->getDocuments()->first();
            $imgUrl = $this->filterService->getUrlOfFilteredImage($this->helper->asset($document, 'imageFile'), 'thumb_350_260');
            $convertedPois[] = [
                'id' => $poi->getId(),
                'timestamp' => $artwork->getCreatedAt()->getTimestamp(),
                'lat' => $poi->getLatitude(),
                'lng' => $poi->getLongitude(),
                'imgUrl' => $imgUrl,
                'caption' => $artwork->getTitle(),
                'iconUrl' => $imgUrl,
                'thumbnail' => $imgUrl,
                'artworkUrl' => $this->router->generate(
                    'artwork',
                    array('id' => $poi->getId())
                ),
            ];
        }

        return json_encode($convertedPois);
    }
}
