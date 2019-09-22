<?php

namespace App\Controller;

use App\Entity\Artwork;
use App\Entity\PageStat;
use App\Entity\User;
use App\Repository\ArtworkRepository;
use App\Repository\PageStatRepository;
use App\Repository\UserRepository;
use Psr\Log\LoggerInterface;
use App\Controller\AbstractController;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class UserController.
 */
class UserController extends AbstractController
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
     * @param int $page
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function __invoke($id = 0, $page = 1)
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
                $userArtworks = $artworkRepository->getArtworksByUser($user, $page, 40);
                $totalUserArtworks = \count($user->getArtworks());
                $userCountriesArtworks = $artworkRepository->getArtworksCountriesByUser($user);
                $pagination = [];
                $colUserArtworks = [];

                if ($totalUserArtworks) {
                    $pagination = [
                        'page' => $page,
                        'route' => $id ? 'public_profile' : 'dashboard',
                        'pages_count' => ceil($totalUserArtworks / 40),
                    ];
                    if ($id) {
                        $pagination['route_params'] = ['id' => $id];
                    } else {
                        $pagination['route_params'] = [];
                    }
                    $columnCount = 4;
                    $colUserArtworks = array_chunk($userArtworks, ceil(\count($userArtworks) / $columnCount));
                }

                $resultViewsTotal = $pageStatRepository->getTotalPageViewsByUser($user);
                $resultViews = $pageStatRepository->getPageViewsByUrl('/public-profile/'.$user->getId());

                $viewsTotal = $resultViewsTotal['sum'] + 1;
                $views = $resultViews['sum'] + 1;

                return $this->render('pages/user_dashboard.html.twig', [
                    'user' => $user,
                    'userArtworks' => $user->getArtworks(),
                    'colUserArtworks' => $colUserArtworks,
                    'pagination' => $pagination,
                    'userCountriesArtworks' => $userCountriesArtworks,
                    'public' => $id,
                    'pageTitle' => $this->translator->trans('title.user', ['%name%' => $user->getUsername()], 'Metas'),
                    'pageDescription' => $this->translator->trans('description.user', ['%name%' => $user->getUsername()], 'Metas'),
                    'views' => $views,
                    'viewsTotal' => $viewsTotal,
                ]);
            } catch (\Exception $e) {
                $this->logger->error($e->getMessage());
                // Nothing to do
            }
        }
        $this->addFlash('warning', $this->translator->trans('user.flash.notice.notfound'));

        return $this->redirectToRoute('contributor_list');
    }
}
