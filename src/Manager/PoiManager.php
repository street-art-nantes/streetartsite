<?php

namespace App\Manager;

use App\Entity\Artwork;
use App\Entity\Document;
use App\Entity\Poi;
use Doctrine\ORM\EntityManagerInterface;

class PoiManager
{
    private $manager;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
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
}
