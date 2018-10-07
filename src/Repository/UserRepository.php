<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * @return mixed
     */
    public function getTopContributor()
    {
        $query = $this->createQueryBuilder('u');

        $query->select('u as user, count(artwork) as nbartwork, count(DISTINCT poi.country) as nbcountry')
            ->leftJoin('u.artworks', 'artwork')
            ->leftJoin('artwork.poi', 'poi')
            ->groupBy('u.id')
            ->orderBy('nbartwork', 'DESC')
            ->setMaxResults(5)
        ;

        return $query->getQuery()->getResult();
    }
}
