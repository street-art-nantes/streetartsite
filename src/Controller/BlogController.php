<?php

namespace App\Controller;

use Contentful\Delivery\Client;
use Contentful\Delivery\Query;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Translation\TranslatorInterface;

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
     * @param string  $id
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function __invoke(Request $request, $id = null)
    {
        $localeArray = $this->getParameter('contentful_locale');
        $entry = '';
        $entries = [];

        /** @var Client $client */
        $client = $this->get('contentful.delivery');

        if ($id && 'list' !== $id) {
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

        $query = new Query();
        $query->setLocale($localeArray[$request->getLocale()])->setContentType('blogPost');

        try {
            $entries = $client->getEntries($query);
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
            $this->addFlash('danger', $this->translator->trans('content.not_found'));
        }

        return $this->render('pages/content.html.twig', [
            'entries' => $entries,
            'pageTitle' => $this->translator->trans('blog.list.title'),
        ]);
    }
}
