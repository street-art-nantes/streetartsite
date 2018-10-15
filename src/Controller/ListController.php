<?php

namespace App\Controller;

use App\Entity\Poi;
use App\Repository\PoiRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ListController extends Controller
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
        ]);
    }
}
