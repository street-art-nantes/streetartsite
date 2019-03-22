<?php

namespace App\Controller;

use App\Entity\Artwork;
use App\Entity\PageStat;
use App\Entity\User;
use App\Repository\ArtworkRepository;
use App\Repository\PageStatRepository;
use App\Repository\UserRepository;
use Psr\Log\LoggerInterface;
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
     * @var LoggerInterface
     */
    private $logger;

    /**
     * UserController constructor.
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

        /** @var PageStatRepository $pageStatRepository */
        $pageStatRepository = $this->getDoctrine()->getRepository(PageStat::class);

        if ($user) {
            try {
                $userArtworks = $artworkRepository->getArtworksByUser($user);
                $userCountriesArtworks = $artworkRepository->getArtworksCountriesByUser($user);

                $resultViewsTotal = $pageStatRepository->getTotalPageViewsByUser($user);
                $resultViews = $pageStatRepository->getPageViewsByUrl('/public-profile/'.$user->getId());

                $viewsTotal = $resultViewsTotal['sum'] + 1;
                $views = $resultViews['sum'] + 1;

                $badges = json_decode($user->getBadges(), true);

                return $this->render('pages/user_dashboard.html.twig', [
                    'user' => $user,
                    'userArtworks' => $userArtworks,
                    'userCountriesArtworks' => $userCountriesArtworks,
                    'public' => $id,
                    'pageTitle' => $this->translator->trans('title.user', ['%name%' => $user->getUsername()], 'Metas'),
                    'pageDescription' => $this->translator->trans('description.user', ['%name%' => $user->getUsername()], 'Metas'),
                    'views' => $views,
                    'viewsTotal' => $viewsTotal,
                    'badges' => $badges,
                ]);
            } catch (\Exception $e) {
                $this->logger->error($e->getMessage());
                // Nothing to do
            }
        }
        $this->addFlash('warning', $this->translator->trans('user.flash.notice.notfound'));

        return $this->redirectToRoute('list');
    }
}
