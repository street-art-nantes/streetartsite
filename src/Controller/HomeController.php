<?php

namespace App\Controller;

use App\Entity\Poi;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class HomeController
 * @package App\Controller
 */
class HomeController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function __invoke()
    {
        $poiRepository = $this->get('doctrine')->getRepository(Poi::class);

        $pois = $poiRepository->findByHighlight(true);

        $columnCount = 3;
        $colPois = array_chunk($pois, ceil(count($pois) / $columnCount));

        return $this->render('pages/home.html.twig', [
            'colPois' => $colPois,
        ]);
    }
}
