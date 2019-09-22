<?php

namespace App\Controller;

use App\Entity\Artwork;
use App\Entity\PageStat;
use App\Entity\Poi;
use App\Manager\PoiManager;
use App\Model\MetasSeo\ArtworkMetasSeo;
use App\Repository\PageStatRepository;
use App\Repository\PoiRepository;
use Psr\Log\LoggerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class ArtworkController extends AbstractController
{
    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * ArtworkController constructor.
     *
     * @param TranslatorInterface $translator
     * @param LoggerInterface $logger
     */
    public function __construct(TranslatorInterface $translator, LoggerInterface $logger)
    {
        $this->translator = $translator;
        $this->logger = $logger;
    }

    /**
     * @param PoiManager $poiManager
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function __invoke(PoiManager $poiManager, $id)
    {
        /** @var PoiRepository $poiRepository */
        $poiRepository = $this->getDoctrine()->getRepository(Poi::class);

        /** @var PageStatRepository $pageStatRepository */
        $pageStatRepository = $this->getDoctrine()->getRepository(PageStat::class);

        $poi = $poiRepository->find($id);
        /** @var Artwork $firstArtwork */
        $firstArtwork = $poi->getArtworks()->first();

        if ($poi && ($firstArtwork->isEnabled() || \in_array('ROLE_ADMIN', $this->getUser()->getRoles(), true))) {
            try {
                $poisAround = $poiRepository->findByDistanceFrom($poi->getLatitude(), $poi->getLongitude());

                $columnCount = 3;
                $colPois = array_chunk($poisAround, ceil(\count($poisAround) / $columnCount));

                /** @var PoiManager $convertedPois */
                $convertedPoi = $poiManager->convertPoisForMap([$poi]);

                $metas = new ArtworkMetasSeo($this->translator);
                $metas->setArtwork($poi->getArtworks()->first());

                $resultViews = $pageStatRepository->getPageViewsByUrl('/artwork/' . $id);

                $views = $resultViews['sum'] + 1;

                return $this->render('pages/artwork.html.twig', [
                    'convertedPoi' => $convertedPoi,
                    'poi' => $poi,
                    'poisAround' => $colPois,
                    'metas' => $metas,
                    'views' => $views,
                ]);
            } catch (\Exception $e) {
                $this->logger->error($e->getMessage());
                // Nothing to do
            }
        }

        $this->addFlash('warning', $this->translator->trans('artwork.flash.notice.notfound'));

        return $this->redirectToRoute('list');
    }
}
