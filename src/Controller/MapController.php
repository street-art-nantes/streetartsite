<?php

namespace App\Controller;

use App\Entity\Poi;
use App\Manager\PoiManager;
use App\Repository\PoiRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MapController extends AbstractController
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function __invoke()
    {
        /** @var PoiRepository $poiRepository */
        $poiRepository = $this->getDoctrine()->getRepository(Poi::class);

        $pois = $poiRepository->getList(1, 10000);

        /** @var PoiManager $poiManager */
        $poiManager = $this->get('poi.manager');
        /** @var PoiManager $convertedPois */
        $convertedPois = $poiManager->convertPoisForMap($pois);

        return $this->render('pages/map.html.twig', [
            'pois' => $convertedPois,
        ]);
    }
}
