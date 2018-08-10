<?php

namespace App\Controller;

use App\Entity\Poi;
use App\Repository\PoiRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ListController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function __invoke()
    {
        /** @var PoiRepository $poiRepository */
        $poiRepository = $this->getDoctrine()->getRepository(Poi::class);

        $pois = $poiRepository->findAll();

        $totalPois = $poiRepository->createQueryBuilder('u')
            ->select('count(u.id)')
            ->getQuery()
            ->getSingleScalarResult();

        $countriesFromPoi = $poiRepository->getAllCountries();

        $columnCount = 4;
        $colPois = array_chunk($pois, ceil(count($pois) / $columnCount));

        return $this->render('pages/list.html.twig', [
            'colPois' => $colPois,
            'totalPois' => $totalPois,
            'totalCountry' => count($countriesFromPoi),
        ]);
    }
}
