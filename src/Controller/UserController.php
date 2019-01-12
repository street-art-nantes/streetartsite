<?php

namespace App\Controller;

use App\Entity\Artwork;
use App\Entity\User;
use App\Repository\ArtworkRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Class UserController.
 */
class UserController extends Controller
{
    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * UserController constructor.
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
    public function __invoke($id = 0)
    {
        /** @var ArtworkRepository $artworkRepository */
        $artworkRepository = $this->getDoctrine()->getRepository(Artwork::class);

        /** @var UserRepository $userRepository */
        $userRepository = $this->getDoctrine()->getRepository(User::class);

        $user = $id ? $userRepository->find($id) : $this->getUser();

        if ($user) {
            try {
                $userArtworks = $artworkRepository->getArtworksByUser($user);
                $userCountriesArtworks = $artworkRepository->getArtworksCountriesByUser($user);

                return $this->render('pages/user_dashboard.html.twig', [
                    'user' => $user,
                    'userArtworks' => $userArtworks,
                    'userCountriesArtworks' => $userCountriesArtworks,
                    'public' => $id,
                ]);
            } catch (\Exception $e) {
                // Nothing to do
            }
        }
        $this->addFlash('warning', $this->translator->trans('user.flash.notice.notfound'));

        return $this->redirectToRoute('list');
    }
}
