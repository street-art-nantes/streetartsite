<?php

namespace App\Controller;

use App\Entity\Author;
use App\Repository\AuthorRepository;
use Symfony\Contracts\Translation\TranslatorInterface;

class ArtistListController extends AbstractController
{
    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * ArtistListController constructor.
     *
     * @param TranslatorInterface $translator
     */
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

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

        $authorsNumber = 500;
        $authors = $authorRepository->getList($page, $authorsNumber);

        $totalAuthors = $authorRepository->createQueryBuilder('u')
            ->select('count(u.id)')
            ->getQuery()
            ->getSingleScalarResult();

        $pagination = [
            'page' => $page,
            'route' => 'artist_list',
            'pages_count' => ceil($totalAuthors / $authorsNumber),
            'route_params' => [],
        ];

        return $this->render('pages/artist_list.html.twig', [
            'authors' => $authors,
            'totalAuthors' => $totalAuthors,
            'pagination' => $pagination,
            'pageTitle' => $this->translator->trans('title.artistlist', [], 'Metas'),
            'pageDescription' => $this->translator->trans('description.artistlist', [], 'Metas'),
        ]);
    }
}
