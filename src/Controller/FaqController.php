<?php

namespace App\Controller;

use Contentful\Delivery\Client;
use Contentful\Delivery\Query;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class FaqController.
 */
class FaqController extends AbstractController
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
     * FaqController constructor.
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
        $entries = [];

        /** @var Client $client */
        $client = $this->get('contentful.delivery');
        $query = new Query();
        $query->setContentType('faq');
        $query->setLocale($localeArray[$request->getLocale()]);
        try {
            $entries = $client->getEntries($query);
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
            $this->addFlash('danger', $this->translator->trans('content.not_found'));
        }

        $entriesTransform = [];
        foreach ($entries as $entryContent) {
            $entriesTransform[] = [
                'title' => $entryContent->get('question'),
                'content' => $entryContent->get('answer'),
            ];
        }

        return $this->render('pages/content.html.twig', [
            'list' => $entriesTransform,
            'pageTitle' => $this->translator->trans('title.faq', [], 'Metas'),
            'pageDescription' => $this->translator->trans('description.faq', [], 'Metas'),
        ]);
    }
}
