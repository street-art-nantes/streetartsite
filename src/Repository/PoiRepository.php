<?php

namespace App\Repository;

use App\Entity\Poi;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Poi|null find($id, $lockMode = null, $lockVersion = null)
 * @method Poi|null findOneBy(array $criteria, array $orderBy = null)
 * @method Poi[]    findAll()
 * @method Poi[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PoiRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Poi::class);
    }

    /**
     * @return mixed
     */
    public function getAllCountries()
    {
        return $this->createQueryBuilder('p')
            ->select('p.country')
            ->distinct()
            ->getQuery()
            ->getResult()
            ;
    }

    /**
     * @param int $page
     * @param int $maxperpage
     *
     * @return mixed
     */
    public function getList($page = 1, $maxperpage = 40)
    {
        $query = $this->createQueryBuilder('p');

        $query->select('p')
            ->leftJoin('p.artworks', 'artworks')
            ->andWhere('artworks.enabled=TRUE')
            ->orderBy('p.id', 'DESC')
        ;

        $query->setFirstResult(($page - 1) * $maxperpage)
            ->setMaxResults($maxperpage)
            ;

        return $query->getQuery()->getResult();
    }

    public function findByDistanceFrom($latitude, $longitude, $distanceInMeters = 100)
    {
        $qb = $this->createQueryBuilder('poi');
        $pointFrom = 'Geography(ST_SetSRID(ST_Point(poi.longitude, poi.latitude), 4326))';
        $pointTo = 'Geography(ST_SetSRID(ST_Point(:longitude, :latitude), 4326))';
        $qb
            ->where($qb->expr()->eq("ST_DWithin($pointFrom, $pointTo, :distance_in_meters)", $qb->expr()->literal(true)))
            ->setParameter('latitude', $latitude)
            ->setParameter('longitude', $longitude)
            ->setParameter('distance_in_meters', $distanceInMeters)
        ;

        return $qb
            ->getQuery()
            ->getResult()
            ;
    }
}
