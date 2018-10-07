<?php

namespace App\Controller;

use App\Entity\Poi;
use App\Entity\User;
use App\Repository\PoiRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class HomeController.
 */
class HomeController extends Controller
{
    /**
     * @throws \Doctrine\ORM\NonUniqueResultException
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function __invoke()
    {
        /** @var PoiRepository $poiRepository */
        $poiRepository = $this->getDoctrine()->getRepository(Poi::class);

        /** @var UserRepository $userRepository */
        $userRepository = $this->getDoctrine()->getRepository(User::class);

        $topContributor = $userRepository->getTopContributor();

        $pois = $poiRepository->findBy(['highlight' => true]);

        $totalPois = $poiRepository->createQueryBuilder('u')
            ->select('count(u.id)')
            ->getQuery()
            ->getSingleScalarResult();

        $countriesFromPoi = $poiRepository->getAllCountries();

        $columnCount = 3;
        $colPois = array_chunk($pois, ceil(count($pois) / $columnCount));

        return $this->render('pages/home.html.twig', [
            'colPois' => $colPois,
            'totalPois' => $totalPois,
            'totalCountry' => count($countriesFromPoi),
            'topContributor' => $topContributor,
        ]);
    }
}
