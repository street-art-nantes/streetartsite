<?php

namespace App\Controller;

use App\Entity\Poi;
use App\Manager\PoiManager;
use App\Repository\PoiRepository;

class MapController extends AbstractController
{
    /**
     * @param PoiManager $poiManager
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function __invoke(PoiManager $poiManager)
    {
        /** @var PoiRepository $poiRepository */
        $poiRepository = $this->getDoctrine()->getRepository(Poi::class);

        $pois = $poiRepository->getList(1, 10000);

        /** @var PoiManager $convertedPois */
        $convertedPois = $poiManager->convertPoisForMap($pois);

        return $this->render('pages/map.html.twig', [
            'pois' => $convertedPois,
        ]);
    }
}
