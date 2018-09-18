<?php

namespace App\Controller;

use App\Entity\Artwork;
use App\Entity\Document;
use App\Entity\User;
use App\Form\Type\ArtworkType;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Class ArtWorkEditController.
 */
class ArtWorkEditController extends Controller
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
     * ArtWorkEditController constructor.
     *
     * @param EntityManagerInterface $entityManager
     * @param LoggerInterface        $logger
     * @param TranslatorInterface    $translator
     */
    public function __construct(EntityManagerInterface $entityManager, LoggerInterface $logger, TranslatorInterface $translator)
    {
        $this->entityManager = $entityManager;
        $this->logger = $logger;
        $this->translator = $translator;
    }

    /**
     * @Route("/artwork/new", name="app_artwork_new")
     * @Route("/artwork/{id}/edit", name="app_artwork_edit")
     *
     * @param Request      $request
     * @param Artwork|null $artwork
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function __invoke(Request $request, Artwork $artwork = null)
    {
        $artwork = $this->initializePoi($artwork);

        $originalDocuments = new ArrayCollection();

        foreach ($artwork->getDocuments() as $document) {
            $originalDocuments->add($document);
        }

        $form = $this->createForm(ArtworkType::class, $artwork);
        $form->handleRequest($request);

        $isCreateForm = !$artwork->getId();

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Artwork $artwork */
            $artwork = $form->getData();

            if ($isCreateForm) {
                $this->entityManager->persist($artwork);
            }

            foreach ($originalDocuments as $document) {
                if (false === $artwork->getDocuments()->contains($document)) {
                    $this->entityManager->remove($document);
                } else {
                    $imagick = new \Imagick($document->getImageFile()->getPathName());
                    $imagick->setImageOrientation(\Imagick::ORIENTATION_TOPLEFT);
                    $imagick->writeImage();
                }
            }

            try {
                $this->entityManager->flush();

                if ($isCreateForm) {
                    return $this->redirectToRoute('app_artwork_new', [
                        'success' => true,
                    ]);
                }
            } catch (\Exception $e) {
                $this->logger->error($e->getMessage());
                $this->addFlash('danger', $this->translator->trans('artwork.flash.danger.error'));
            }
        }

        return $this->render('/pages/artwork_edit.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param Artwork|null $artwork
     *
     * @return Artwork
     */
    private function initializePoi(Artwork $artwork = null)
    {
        if (!$artwork) {
            $artwork = new Artwork();
            $artwork->setEnabled(false);
            $artwork->setType('graffiti');
            $artwork->addDocument(new Document());
        }

        if (!$artwork->getContributor() && $this->getUser() instanceof User) {
            $artwork->setContributor($this->getUser());
        }

        return $artwork;
    }
}
