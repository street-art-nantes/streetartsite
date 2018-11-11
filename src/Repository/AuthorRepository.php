<?php

namespace App\Repository;

use App\Entity\Author;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Author|null find($id, $lockMode = null, $lockVersion = null)
 * @method Author|null findOneBy(array $criteria, array $orderBy = null)
 * @method Author[]    findAll()
 * @method Author[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AuthorRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Author::class);
    }

    /**
     * @param int $page
     * @param int $maxperpage
     *
     * @return mixed
     */
    public function getList($page = 1, $maxperpage = 40)
    {
        $query = $this->createQueryBuilder('a');

        $query->select('a as artist, count(artworks) as nbartwork')
            ->leftJoin('a.artworks', 'artworks')
            ->leftJoin('artworks.poi', 'poi')
            ->groupBy('a.id')
            ->orderBy('nbartwork', 'DESC')
        ;

        $query->setFirstResult(($page - 1) * $maxperpage)
            ->setMaxResults($maxperpage)
        ;

        return $query->getQuery()->getResult();
    }
}
