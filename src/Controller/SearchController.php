<?php

namespace App\Controller;

use App\Entity\Poi;
use App\Repository\PoiRepository;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class SearchController extends Controller
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * SearchController constructor.
     *
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @param string $queryRequest
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function __invoke($queryRequest)
    {
        /** @var PoiRepository $poiRepository */
        $poiRepository = $this->getDoctrine()->getRepository(Poi::class);

        $query = [];
        $pois = [];
        $colPois = [];

        $tmp = explode('-', $queryRequest, 2);
        $query[$tmp[0]] = $tmp[1];

        try {
            $pois = $poiRepository->findBy($query);
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
        }

        if ($pois) {
            $columnCount = 4;
            $colPois = array_chunk($pois, ceil(\count($pois) / $columnCount));
        }

        return $this->render('pages/list.html.twig', [
            'colPois' => $colPois,
            'filterResult' => \count($pois),
            'listOfCountry' => $poiRepository->getDistinctCountries(),
            'listOfCity' => $poiRepository->getDistinctCities(),
        ]);
    }
}
