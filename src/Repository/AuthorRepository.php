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
    public function getList($page = 1, $maxperpage = 100)
    {
        $query = $this->createQueryBuilder('a');

        $query->select('a as artist')
            ->andWhere('a.enabled=TRUE')
            ->groupBy('a.id')
            ->orderBy('a.name', 'ASC')
        ;

        $query->setFirstResult(($page - 1) * $maxperpage)
            ->setMaxResults($maxperpage)
        ;

        return $query->getQuery()->getResult();
    }
}
