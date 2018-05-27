<?php

namespace App\Controller;

use App\Entity\Poi;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class MapController extends Controller
{
    public function __invoke()
    {
        $poiRepository = $this->get('doctrine')->getRepository(Poi::class);

        $pois = $poiRepository->findByHighlight(true);

        $convertedPois = $this->get('poi.manager')->convertPoisForMap($pois);

        return $this->render('pages/map.html.twig', [
            'pois' => $convertedPois,
        ]);
    }
}
