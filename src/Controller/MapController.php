<?php

namespace App\Controller;

use App\Entity\Poi;
use App\Manager\PoiManager;
use App\Repository\PoiRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class MapController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function __invoke()
    {
        /** @var PoiRepository $poiRepository */
        $poiRepository = $this->getDoctrine()->getRepository(Poi::class);

//        $pois = $poiRepository->findBy(['highlight' => true]);
        $pois = $poiRepository->findAll();

        /** @var PoiManager $poiManager */
        $poiManager = $this->get('poi.manager');
        /** @var PoiManager $convertedPois */
        $convertedPois = $poiManager->convertPoisForMap($pois);

        return $this->render('pages/map.html.twig', [
            'pois' => $convertedPois,
        ]);
    }
}
