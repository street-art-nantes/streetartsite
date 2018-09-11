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
        ;

        $query->setFirstResult(($page - 1) * $maxperpage)
            ->setMaxResults($maxperpage)
            ;

        return $query->getQuery()->getResult();
    }
}
