<?php

namespace App\Manager;

use App\Entity\Artwork;
use App\Entity\Document;
use App\Entity\Poi;
use Doctrine\ORM\EntityManagerInterface;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;

/**
 * Class PoiManager
 * @package App\Manager
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
     * PoiManager constructor.
     * @param EntityManagerInterface $manager
     * @param UploaderHelper $helper
     */
    public function __construct(EntityManagerInterface $manager, UploaderHelper $helper)
    {
        $this->manager = $manager;
        $this->helper = $helper;
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
            $convertedPois[] = [
                'id' => $poi->getId(),
                'timestamp' => $artwork->getCreatedAt()->getTimestamp(),
                'lat' => $poi->getLatitude(),
                'lng' => $poi->getLongitude(),
                'url' => $this->helper->asset($document, 'imageFile'),
                'caption' => $artwork->getTitle(),
                'iconUrl' => $this->helper->asset($document, 'imageFile'),
                'thumbnail' => $this->helper->asset($document, 'imageFile'),
            ];
        }

        return json_encode($convertedPois);
    }
}
