<?php

namespace App\Controller;

use App\Entity\Poi;
use App\Entity\User;
use App\Repository\PoiRepository;
use App\Repository\UserRepository;
use App\Controller\AbstractController;

/**
 * Class HomeController.
 */
class HomeController extends AbstractController
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

        if (!\count($pois)) {
            $colPois = [];
        } else {
            $colPois = array_chunk($pois, ceil(\count($pois) / $columnCount));
        }

        return $this->render('pages/home.html.twig', [
            'colPois' => $colPois,
            'totalPois' => $totalPois,
            'totalCountry' => \count($countriesFromPoi),
            'topContributor' => $topContributor,
        ]);
    }
}
