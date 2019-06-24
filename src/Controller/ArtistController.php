<?php

namespace App\Controller;

use App\Entity\Artwork;
use App\Entity\Author;
use App\Entity\PageStat;
use App\Model\MetasSeo\AuthorMetasSeo;
use App\Repository\ArtworkRepository;
use App\Repository\AuthorRepository;
use App\Repository\PageStatRepository;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Class ArtistController.
 */
class ArtistController extends Controller
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
     * ArtistController constructor.
     *
     * @param TranslatorInterface $translator
     * @param LoggerInterface     $logger
     */
    public function __construct(TranslatorInterface $translator, LoggerInterface $logger)
    {
        $this->translator = $translator;
        $this->logger = $logger;
    }

    /**
     * @param Request $request
     * @param int     $id
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function __invoke(Request $request, $id = 0)
    {
        /** @var AuthorRepository $artistRepository */
        $artistRepository = $this->getDoctrine()->getRepository(Author::class);

        /** @var ArtworkRepository $artworkRepository */
        $artworkRepository = $this->getDoctrine()->getRepository(Artwork::class);

        /** @var PageStatRepository $pageStatRepository */
        $pageStatRepository = $this->getDoctrine()->getRepository(PageStat::class);

        $artist = $artistRepository->find($id);

        if ($artist) {
            try {
                $artistArtworks = $artworkRepository->getArtworksByAuthor($artist);
                $artistCountriesArtworks = $artworkRepository->getArtworksCountriesByAuthor($artist);

                $metas = new AuthorMetasSeo($this->translator, $request->getLocale());
                $metas->setAuthor($artist);

                $resultViewsTotal = $pageStatRepository->getTotalPageViewsByArtist($artist);
                $resultViews = $pageStatRepository->getPageViewsByUrl('/artist-profile/'.$artist->getId());

                $viewsTotal = $resultViewsTotal['sum'] + 1;
                $views = $resultViews['sum'] + 1;

                return $this->render('pages/artist_dashboard.html.twig', [
                    'artist' => $artist,
                    'artistArtworks' => $artistArtworks,
                    'artistCountriesArtworks' => $artistCountriesArtworks,
                    'public' => $id,
                    'metas' => $metas,
                    'views' => $views,
                    'viewsTotal' => $viewsTotal,
                ]);
            } catch (\Exception $e) {
                $this->logger->error($e->getMessage());
                // Nothing to do
            }
        }

        $this->addFlash('warning', $this->translator->trans('artist.flash.notice.notfound'));

        return $this->redirectToRoute('artist_list');
    }
}
