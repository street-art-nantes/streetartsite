<?php

namespace App\Controller;

use App\Model\MetasSeo\BlogMetasSeo;
use Contentful\Delivery\Client;
use Contentful\Delivery\Query;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Contracts\Translation\TranslatorInterface;

class BlogController extends AbstractController
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
     * @param LoggerInterface $logger
     * @param TranslatorInterface $translator
     */
    public function __construct(LoggerInterface $logger, TranslatorInterface $translator)
    {
        $this->logger = $logger;
        $this->translator = $translator;
    }

    /**
     * @param Request $request
     * @param string $id
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function __invoke(Request $request, $id = null)
    {
        $localeArray = $this->getParameter('contentful_locale');
        $entry = '';
        $entries = [];

        if ('preview' === $request->attributes->get('_route')) {
            /** @var Client $client */
            $client = $this->get('contentful.delivery.streetartapi_preview_client');
        } else {
            /** @var Client $client */
            $client = $this->get('contentful.delivery.streetartapi_client');
        }

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

            $metas = new BlogMetasSeo($this->translator);
            $metas->setEntry($entry);

            return $this->render('pages/content.html.twig', [
                'entry' => $entry,
                'metas' => $metas,
                'pageTitle' => $metas->getPageTitle(),
                'pageDescription' => $metas->getPageDescription(),
            ]);
        }

        $query = new Query();
        $query->setLocale($localeArray[$request->getLocale()])->setContentType('blogPost')
            ->orderBy('fields.publishedDate', true);

        try {
            $entries = $client->getEntries($query);
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
            $this->addFlash('danger', $this->translator->trans('content.not_found'));
        }

        return $this->render('pages/content.html.twig', [
            'entries' => $entries,
            'pageTitle' => $this->translator->trans('title.blog', [], 'Metas'),
            'pageDescription' => $this->translator->trans('description.blog', [], 'Metas'),
        ]);
    }
}
