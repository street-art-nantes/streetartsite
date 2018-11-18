<?php

namespace App\Controller;

use App\Entity\Author;
use App\Repository\AuthorRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ArtistListController extends Controller
{
    /**
     * @param int $page
     *
     * @throws \Doctrine\ORM\NonUniqueResultException
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function __invoke($page)
    {
        /** @var AuthorRepository $authorRepository */
        $authorRepository = $this->getDoctrine()->getRepository(Author::class);

        $authors = $authorRepository->getList($page);

        $totalAuthors = $authorRepository->createQueryBuilder('u')
            ->select('count(u.id)')
            ->getQuery()
            ->getSingleScalarResult();

        $pagination = [
            'page' => $page,
            'route' => 'list',
            'pages_count' => ceil($totalAuthors / 40),
            'route_params' => [],
        ];

        $columnCount = 4;
        $colAuthors = array_chunk($authors, ceil(\count($authors) / $columnCount));

        return $this->render('pages/artist_list.html.twig', [
            'colAuthors' => $colAuthors,
            'totalAuthors' => $totalAuthors,
            'pagination' => $pagination,
        ]);
    }
}