<?php

namespace App\Controller;

use App\Entity\Artwork;
use App\Entity\Document;
use App\Entity\User;
use App\Form\Type\ArtworkType;
use App\Service\Mailer;
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
     * @Route("/{_locale}/artwork/new", name="app_artwork_new")
     * @Route("/{_locale}/artwork/{id}/edit", name="app_artwork_edit")
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

            try {
                foreach ($originalDocuments as $document) {
                    if (false === $artwork->getDocuments()->contains($document)) {
                        $this->entityManager->remove($document);
                    } else {
                        $imagick = new \Imagick();
                        $imagick->readImage($document->getImageFile()->getPathName());
                        $this->autorotate($imagick);
                        $imagick->writeImage();
                    }
                }

                $this->entityManager->flush();

                if ($isCreateForm) {
                    $this->mailer->sendSubmissionEmailMessage($request, $this->getUser());

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
            'user' => $this->getUser(),
            'pageTitle' => $this->translator->trans('title.artworkedit', [], 'Metas'),
            'pageDescription' => $this->translator->trans('description.artworkedit', [], 'Metas'),
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

    /**
     * @param \Imagick $image
     *
     * @return \Imagick
     */
    public function autorotate(\Imagick $image)
    {
        switch ($image->getImageOrientation()) {
            case \Imagick::ORIENTATION_TOPLEFT:
                break;
            case \Imagick::ORIENTATION_TOPRIGHT:
                $image->flopImage();
                break;
            case \Imagick::ORIENTATION_BOTTOMRIGHT:
                $image->rotateImage('#000', 180);
                break;
            case \Imagick::ORIENTATION_BOTTOMLEFT:
                $image->flopImage();
                $image->rotateImage('#000', 180);
                break;
            case \Imagick::ORIENTATION_LEFTTOP:
                $image->flopImage();
                $image->rotateImage('#000', -90);
                break;
            case \Imagick::ORIENTATION_RIGHTTOP:
                $image->rotateImage('#000', 90);
                break;
            case \Imagick::ORIENTATION_RIGHTBOTTOM:
                $image->flopImage();
                $image->rotateImage('#000', 90);
                break;
            case \Imagick::ORIENTATION_LEFTBOTTOM:
                $image->rotateImage('#000', -90);
                break;
            default: // Invalid orientation
                break;
        }
        $image->setImageOrientation(\Imagick::ORIENTATION_TOPLEFT);

        return $image;
    }
}
