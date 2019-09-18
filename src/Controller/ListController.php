<?php

namespace App\Controller;

use App\Entity\Poi;
use App\Repository\PoiRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Contracts\Translation\TranslatorInterface;

class ListController extends AbstractController
{
    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * ListController constructor.
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
        /** @var PoiRepository $poiRepository */
        $poiRepository = $this->getDoctrine()->getRepository(Poi::class);

        $pois = $poiRepository->getList($page);

        $totalPois = $poiRepository->createQueryBuilder('u')
            ->select('count(u.id)')
            ->getQuery()
            ->getSingleScalarResult();

        $pagination = [
            'page' => $page,
            'route' => 'list',
            'pages_count' => ceil($totalPois / 40),
            'route_params' => [],
        ];

        $countriesFromPoi = $poiRepository->getAllCountries();

        $columnCount = 4;
        $colPois = array_chunk($pois, ceil(\count($pois) / $columnCount));

        return $this->render('pages/list.html.twig', [
            'colPois' => $colPois,
            'totalPois' => $totalPois,
            'totalCountry' => \count($countriesFromPoi),
            'pagination' => $pagination,
            'listOfCountry' => $poiRepository->getDistinctCountries(),
            'listOfCity' => $poiRepository->getDistinctCities(),
            'pageTitle' => $this->translator->trans('title.list', [], 'Metas'),
            'pageDescription' => $this->translator->trans('description.list', [], 'Metas'),
        ]);
    }
}
