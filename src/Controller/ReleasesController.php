<?php

namespace App\Controller;

use Contentful\Delivery\Client;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Class ReleasesController.
 */
class ReleasesController extends Controller
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * ReleasesController constructor.
     *
     * @param LoggerInterface     $logger
     * @param TranslatorInterface $translator
     */
    public function __construct(LoggerInterface $logger, TranslatorInterface $translator)
    {
        $this->logger = $logger;
        $this->translator = $translator;
    }

    /**
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function __invoke(Request $request)
    {
        $localeArray = $this->getParameter('contentful_locale');
        $entry = '';

        /** @var Client $client */
        $client = $this->get('contentful.delivery');
        try {
            $entry = $client->getEntry('5I57qW3gTSAEogWCKm8iIQ', $localeArray[$request->getLocale()]);
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
            $this->addFlash('danger', $this->translator->trans('content.not_found'));
        }

        if (!$entry) {
            throw new NotFoundHttpException();
        }

        return $this->render('pages/content.html.twig', [
            'blog' => $entry,
            'pageTitle' => $this->translator->trans('title.releases', [], 'Metas'),
            'pageDescription' => $this->translator->trans('description.releases', [], 'Metas'),
        ]);
    }
}
