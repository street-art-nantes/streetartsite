<?php

namespace App\Controller;

use App\Entity\Artwork;
use App\Entity\Author;
use App\Model\MetasSeo\AuthorMetasSeo;
use App\Repository\ArtworkRepository;
use App\Repository\AuthorRepository;
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
     * ArtistController constructor.
     *
     * @param TranslatorInterface $translator
     */
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
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

        $artist = $artistRepository->find($id);

        if ($artist) {
            try {
                $artistArtworks = $artworkRepository->getArtworksByAuthor($artist);
                $artistCountriesArtworks = $artworkRepository->getArtworksCountriesByAuthor($artist);

                return $this->render('pages/artist_dashboard.html.twig', [
                    'artist' => $artist,
                    'artistArtworks' => $artistArtworks,
                    'artistCountriesArtworks' => $artistCountriesArtworks,
                    'public' => $id,
                ]);
            } catch (\Exception $e) {
                // Nothing to do
            }
        }

        $this->addFlash('warning', $this->translator->trans('artist.flash.notice.notfound'));

        $metas = new AuthorMetasSeo($this->translator, $request->getLocale());
        $metas->setAuthor($artist);

        return $this->render('pages/artist_dashboard.html.twig', [
            'artist' => $artist,
            'artistArtworks' => $artistArtworks,
            'artistCountriesArtworks' => $artistCountriesArtworks,
            'public' => $id,
            'metas' => $metas,
        ]);
    }
}
