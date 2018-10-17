<?php

namespace App\Controller;

use App\Entity\Artwork;
use App\Repository\ArtworkRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class UserController.
 */
class UserController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function __invoke()
    {
        /** @var ArtworkRepository $artworkRepository */
        $artworkRepository = $this->getDoctrine()->getRepository(Artwork::class);

        $user = $this->getUser();

        $userArtworks = $artworkRepository->getArtworksByUser($user);
        $userCountriesArtworks = $artworkRepository->getArtworksCountriesByUser($user);

        return $this->render('pages/user_dashboard.html.twig', [
            'user' => $user,
            'userArtworks' => $userArtworks,
            'userCountriesArtworks' => $userCountriesArtworks,
        ]);
    }
}
