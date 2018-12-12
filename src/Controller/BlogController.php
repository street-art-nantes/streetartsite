<?php

namespace App\Controller;

use Contentful\Delivery\Client;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Translation\TranslatorInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;

class BlogController extends Controller
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
     * BlogController constructor.
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
     * @param string $id
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function __invoke(Request $request, $id)
    {
        $localeArray = $this->getParameter('contentful_locale');

        /** @var Client $client */
        $client = $this->get('contentful.delivery');
        try {
            $entry = $client->getEntry($id, $localeArray[$request->getLocale()]);
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
            $this->addFlash('danger', $this->translator->trans('content.not_found'));
        }

        if (!$entry) {
            throw new NotFoundHttpException();
        }

        return $this->render('pages/content.html.twig', [
            'entry' => $entry,
            'pageTitle' => $entry->get('title'),
        ]);
    }
}
