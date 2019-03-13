<?php

namespace App\Repository;

use App\Entity\Artwork;
use App\Entity\Author;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Artwork|null find($id, $lockMode = null, $lockVersion = null)
 * @method Artwork|null findOneBy(array $criteria, array $orderBy = null)
 * @method Artwork[]    findAll()
 * @method Artwork[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArtworkRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Artwork::class);
    }

    /**
     * @param User $user
     *
     * @return mixed
     */
    public function getArtworksByUser(User $user)
    {
        $query = $this->createQueryBuilder('a');

        $query->select('a')
            ->leftJoin('a.contributor', 'users')
            ->andWhere('users.id = :user')
            ->andWhere('a.enabled=TRUE')
            ->setParameter('user', $user)
            ->orderBy('a.id', 'DESC')
        ;

        return $query->getQuery()->getResult();
    }

    /**
     * @param Author $author
     *
     * @return mixed
     */
    public function getArtworksByAuthor(Author $author)
    {
        $query = $this->createQueryBuilder('a');

        $query->select('a')
            ->leftJoin('a.author', 'author')
            ->andWhere('author.id = :artist')
            ->andWhere('a.enabled=TRUE')
            ->setParameter('artist', $author)
            ->orderBy('a.id', 'DESC')
        ;

        return $query->getQuery()->getResult();
    }

    /**
     * @param User $user
     *
     * @return mixed
     */
    public function getArtworksCountriesByUser(User $user)
    {
        $query = $this->createQueryBuilder('a');

        $query->select('pois.country')
            ->leftJoin('a.contributor', 'users')
            ->leftJoin('a.poi', 'pois')
            ->andWhere('users.id = :user')
            ->andWhere('a.enabled=TRUE')
            ->setParameter('user', $user)
            ->groupBy('pois.country')
            ->orderBy('pois.country', 'ASC')
        ;

        return $query->getQuery()->getResult();
    }

    /**
     * @param User $user
     *
     * @return mixed
     */
    public function getArtworksCitiesByUser(User $user)
    {
        $query = $this->createQueryBuilder('a');

        $query->select('pois.city')
            ->leftJoin('a.contributor', 'users')
            ->leftJoin('a.poi', 'pois')
            ->andWhere('users.id = :user')
            ->andWhere('a.enabled=TRUE')
            ->setParameter('user', $user)
            ->groupBy('pois.city')
            ->orderBy('pois.city', 'ASC')
        ;

        return $query->getQuery()->getResult();
    }

    /**
     * @param Author $author
     *
     * @return mixed
     */
    public function getArtworksCountriesByAuthor(Author $author)
    {
        $query = $this->createQueryBuilder('a');

        $query->select('pois.country')
            ->leftJoin('a.author', 'author')
            ->leftJoin('a.poi', 'pois')
            ->andWhere('author.id = :artist')
            ->andWhere('a.enabled=TRUE')
            ->setParameter('artist', $author)
            ->groupBy('pois.country')
            ->orderBy('pois.country', 'ASC')
        ;

        return $query->getQuery()->getResult();
    }
}
