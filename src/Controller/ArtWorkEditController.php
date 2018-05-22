<?php

namespace App\Controller;

use App\Entity\Artwork;
use App\Entity\Document;
use App\Entity\User;
use App\Form\Type\ArtworkType;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

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
     * ArtWorkEditController constructor.
     *
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/artwork/new", name="app_artwork_new")
     * @Route("/artwork/{id}/edit", name="app_artwork_edit")
     *
     * @param Request $request
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

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Artwork $artwork */
            $artwork = $form->getData();

            if (!$artwork->getId()) {
                $this->entityManager->persist($artwork);
            }

            foreach ($originalDocuments as $document) {
                if (false === $artwork->getDocuments()->contains($document)) {
                    $this->entityManager->remove($document);
                }
            }

            try {
                $this->entityManager->flush();

                $this->addFlash('success', 'artwork.flash.success.saved');
            } catch (\Exception $e) {
                $this->addFlash('danger', 'artwork.flash.danger.error');
            }

            return $this->redirectToRoute('app_artwork_edit', [
                'id' => $artwork->getId(),
            ]);
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
            $artwork->setType('test');
            $artwork->addDocument(new Document());
        }

        if (!$artwork->getContributor() && $this->getUser() instanceof User) {
            $artwork->setContributor($this->getUser());
        }

        return $artwork;
    }
}
