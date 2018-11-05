<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ContributorListController extends Controller
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
        /** @var UserRepository $poiRepository */
        $contributorRepository = $this->getDoctrine()->getRepository(User::class);

        $contributors = $contributorRepository->getList($page);

        $totalContributors = $contributorRepository->createQueryBuilder('u')
            ->select('count(u.id)')
            ->getQuery()
            ->getSingleScalarResult();

        $pagination = [
            'page' => $page,
            'route' => 'list',
            'pages_count' => ceil($totalContributors / 40),
            'route_params' => [],
        ];

        return $this->render('pages/contributors_list.html.twig', [
            'pagination' => $pagination,
            'contributors' => $contributors,
        ]);
    }
}
