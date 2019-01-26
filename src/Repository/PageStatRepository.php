<?php

namespace App\Repository;

use App\Entity\Author;
use App\Entity\PageStat;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method PageStat|null find($id, $lockMode = null, $lockVersion = null)
 * @method PageStat|null findOneBy(array $criteria, array $orderBy = null)
 * @method PageStat[]    findAll()
 * @method PageStat[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PageStatRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, PageStat::class);
    }

    /**
     * @param string $url
     *
     * @throws \Doctrine\DBAL\DBALException
     *
     * @return mixed
     */
    public function getPageViewsByUrl($url)
    {
        $sqlRaw = 'SELECT SUM(p.views) FROM page_stat as p WHERE p.path SIMILAR TO \'%'.$url.'|%'.$url.'\?%|%'.$url.'\#%\'';

        $statement = $this->getEntityManager()->getConnection()->prepare($sqlRaw);

        $statement->execute();

        return $statement->fetch();
    }

    /**
     * @param Author $artist
     *
     * @throws \Doctrine\DBAL\DBALException
     *
     * @return mixed
     */
    public function getTotalPageViewsByArtist(Author $artist)
    {
        $sqlRaw = 'SELECT SUM(p.views) FROM page_stat as p
          INNER JOIN (
          SELECT artwork.poi_id FROM artwork INNER JOIN artwork_author a on artwork.id = a.artwork_id
          WHERE
          a.author_id = :author
          ) art
          ON p.path SIMILAR TO \'%/artwork/\' || art.poi_id || \'([?|#]%|)\'
          ';

        $statement = $this->getEntityManager()->getConnection()->prepare($sqlRaw);

        $statement->bindValue('author', $artist->getId());
        $statement->execute();

        return $statement->fetch();
    }

    /**
     * @param User $user
     *
     * @throws \Doctrine\DBAL\DBALException
     *
     * @return mixed
     */
    public function getTotalPageViewsByUser(User $user)
    {
        $sqlRaw = 'SELECT SUM(p.views) FROM page_stat as p
          INNER JOIN (
                       SELECT artwork.poi_id FROM artwork
                       WHERE
                         artwork.contributor_id = :user
                     ) art
            ON p.path SIMILAR TO \'%/artwork/\' || art.poi_id || \'([?|#]%|)\'';

        $statement = $this->getEntityManager()->getConnection()->prepare($sqlRaw);

        $statement->bindValue('user', $user->getId());
        $statement->execute();

        return $statement->fetch();
    }
}
