<?php
namespace App\Controller;

use App\Entity\Poi;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class IndexController extends Controller
{

    public function __invoke()
    {
        $poiRepository = $this->get('doctrine')->getRepository(Poi::class);

        $pois = $poiRepository->findByHighlight(true);

        return $this->render('pages/home.html.twig', [
            'pois' => $pois,
        ]);
    }
}