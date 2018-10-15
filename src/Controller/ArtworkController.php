<?php

namespace App\Controller;

use App\Entity\Poi;
use App\Manager\PoiManager;
use App\Repository\PoiRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ArtworkController extends Controller
{
    /**
     * @param int $id
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function __invoke($id)
    {
        /** @var PoiRepository $poiRepository */
        $poiRepository = $this->getDoctrine()->getRepository(Poi::class);

        $poi = $poiRepository->find($id);

        $poisAround = $poiRepository->findByDistanceFrom($poi->getLatitude(), $poi->getLongitude());

        $columnCount = 3;
        $colPois = array_chunk($poisAround, ceil(\count($poisAround) / $columnCount));

        /** @var PoiManager $poiManager */
        $poiManager = $this->get('poi.manager');
        /** @var PoiManager $convertedPois */
        $convertedPoi = $poiManager->convertPoisForMap([$poi]);

        return $this->render('pages/artwork.html.twig', [
            'convertedPoi' => $convertedPoi,
            'poi' => $poi,
            'poisAround' => $colPois,
        ]);
    }
}
