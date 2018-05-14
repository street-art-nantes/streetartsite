<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class IndexController extends Controller
{

    public function __invoke()
    {
        //TODO get last or best document to show on home
        $datas = '[{"id": "1", "timestamp": "1524862300", "lat": "47.2046278293756", "lng": "-1.5627931420681307", "url": "/img/P1030409.jpg", "caption": "plop1", "iconUrl": "/img/P1030409.jpg", "thumbnail": "/img/P1030409.jpg"},' .
        '{"id": "2", "timestamp": "1524862300", "lat": "47.1995894480958", "lng": "-1.580989248025162", "url": "/img/P1030410.jpg", "caption": "plop2", "iconUrl": "/img/P1030410.jpg", "thumbnail": "/img/P1030410.jpg"},' .
        '{"id": "3", "timestamp": "1524862300", "lat": "47.181753000672344", "lng": "-1.525456792580826", "url": "/img/P1030414.jpg", "caption": "plop3", "iconUrl": "/img/P1030414.jpg", "thumbnail": "/img/P1030414.jpg"}]';

        return $this->render('pages/home.html.twig', [
            'datas' => json_decode($datas),
        ]);
    }
}