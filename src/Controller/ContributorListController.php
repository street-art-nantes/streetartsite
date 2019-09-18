<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Contracts\Translation\TranslatorInterface;

class ContributorListController extends AbstractController
{
    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * ContributorListController constructor.
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
        /** @var UserRepository $contributorRepository */
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
            'pageTitle' => $this->translator->trans('title.contributorlist', [], 'Metas'),
            'pageDescription' => $this->translator->trans('description.contributorlist', [], 'Metas'),
        ]);
    }
}
