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
        $poiRepository = $this->get('doctrine')->getRepository(Poi::class);

        $pois = $poiRepository->findByHighlight(true);

        /** @var PoiManager $convertedPois */
        $convertedPois = $this->get('poi.manager')->convertPoisForMap($pois);

        return $this->render('pages/map.html.twig', [
            'pois' => $convertedPois,
        ]);
    }
}
