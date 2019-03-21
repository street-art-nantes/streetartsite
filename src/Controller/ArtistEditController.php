<?php

namespace App\Controller;

use App\Entity\Author;
use App\Entity\User;
use App\Form\Type\ArtistType;
use App\Service\Mailer;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Class ArtistEditController.
 */
class ArtistEditController extends Controller
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @var Mailer
     */
    private $mailer;

    /**
     * ArtWorkEditController constructor.
     *
     * @param EntityManagerInterface $entityManager
     * @param LoggerInterface        $logger
     * @param TranslatorInterface    $translator
     * @param Mailer                 $mailer
     */
    public function __construct(EntityManagerInterface $entityManager, LoggerInterface $logger, TranslatorInterface $translator, Mailer $mailer)
    {
        $this->entityManager = $entityManager;
        $this->logger = $logger;
        $this->translator = $translator;
        $this->mailer = $mailer;
    }

    /**
     * @Route("/{_locale}/artist/new", name="app_artist_new")
     * @Route("/{_locale}/artist/{id}/edit", name="app_artist_edit")
     *
     * @param Request      $request
     * @param Author|null $author
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function __invoke(Request $request, Author $author = null)
    {
        if (!$author) {
            $author = new Author();
            if (!$author->getContributor() && $this->getUser() instanceof User) {
                $author->setContributor($this->getUser());
            }
        }

        $form = $this->createForm(ArtistType::class, $author);
        $form->handleRequest($request);

        $isCreateForm = !$author->getId();

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Author $author */
            $author = $form->getData();

            if ($isCreateForm) {
                $this->entityManager->persist($author);
            }

            try {
                $this->entityManager->flush();

                if ($isCreateForm) {
                    if ($this->getUser()) {
                        $this->mailer->sendArtistSubmissionEmailMessage($request, $this->getUser());
                    }

                    return $this->redirectToRoute('app_artist_new', [
                        'success' => true,
                    ]);
                }
            } catch (\Exception $e) {
                $this->logger->error($e->getMessage());
                $this->addFlash('danger', $this->translator->trans('artist.flash.danger.error'));
            }
        }

        return $this->render('/pages/artist_edit.twig', [
            'form' => $form->createView(),
            'user' => $this->getUser(),
            'pageTitle' => $this->translator->trans('title.artistedit', [], 'Metas'),
            'pageDescription' => $this->translator->trans('description.artistedit', [], 'Metas'),
        ]);
    }

}
