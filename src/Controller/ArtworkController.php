<?php

namespace App\Controller;

use App\Entity\Poi;
use App\Manager\PoiManager;
use App\Model\MetasSeo\ArtworkMetasSeo;
use App\Repository\PoiRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Translation\TranslatorInterface;

class ArtworkController extends Controller
{
    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * ArtworkController constructor.
     *
     * @param TranslatorInterface $translator
     */
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

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

        if ($poi) {
            try {
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
            } catch (\Exception $e) {
                // Nothing to do
            }
        }

        $this->addFlash('warning', $this->translator->trans('artwork.flash.notice.notfound'));

        $metas = new ArtworkMetasSeo($this->translator);
        $metas->setArtwork($poi->getArtworks()->first());

        return $this->render('pages/artwork.html.twig', [
            'convertedPoi' => $convertedPoi,
            'poi' => $poi,
            'poisAround' => $colPois,
            'metas' => $metas,
        ]);
    }
}
