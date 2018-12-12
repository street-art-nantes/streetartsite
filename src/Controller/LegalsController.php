<?php

namespace App\Controller;

use Contentful\Delivery\Client;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Translation\TranslatorInterface;
use Psr\Log\LoggerInterface;

/**
 * Class LegalsController.
 */
class LegalsController extends Controller
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
     * LegalsController constructor.
     *
     * @param LoggerInterface        $logger
     * @param TranslatorInterface    $translator
     */
    public function __construct(LoggerInterface $logger, TranslatorInterface $translator)
    {
        $this->logger = $logger;
        $this->translator = $translator;
    }

    /**
     * @param Request      $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function __invoke(Request $request)
    {
        $localeArray = $this->getParameter('contentful_locale');

        /** @var Client $client */
        $client = $this->get('contentful.delivery');

        try {
            $entry = $client->getEntry('5o9QHWZhTyouo2oIiGEOkw', $localeArray[$request->getLocale()]);
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
            $this->addFlash('danger', $this->translator->trans('content.not_found'));
        }

        if (!$entry) {
            throw new NotFoundHttpException();
        }

        return $this->render('pages/content.html.twig', [
            'blog' => $entry,
            'pageTitle' => $entry->get('title'),
        ]);
    }
}
