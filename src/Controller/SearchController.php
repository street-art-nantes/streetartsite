<?php

namespace App\Controller;

use App\Entity\Poi;
use App\Repository\PoiRepository;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Contracts\Translation\TranslatorInterface;

class SearchController extends AbstractController
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * SearchController constructor.
     *
     * @param LoggerInterface $logger
     * @param TranslatorInterface $translator
     */
    public function __construct(LoggerInterface $logger, TranslatorInterface $translator)
    {
        $this->logger = $logger;
        $this->translator = $translator;
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
            $pois = $poiRepository->searchByCriteria($query);
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
            'pageTitle' => $this->translator->trans('title.search', ['%place%' => $tmp[1]], 'Metas'),
            'pageDescription' => $this->translator->trans('description.search', ['%place%' => $tmp[1]], 'Metas'),
        ]);
    }
}
