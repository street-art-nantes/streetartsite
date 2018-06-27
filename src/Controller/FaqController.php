<?php

namespace App\Controller;

use Contentful\Delivery\Client;
use Contentful\Delivery\Query;
use Contentful\Delivery\Resource\Entry;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class FaqController.
 */
class FaqController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function __invoke()
    {
        /** @var Client $client */
        $client = $this->get('contentful.delivery');
        /** @var Entry $entry */
        $entry = $client->getEntry('36gXCzBGjCCgog8aYqeaoK');
        $query = new Query();
        $query->setContentType('faq');
        $entries = $client->getEntries($query);

        if (!$entry) {
            throw new NotFoundHttpException();
        }

        $entriesTransform = [];
        foreach ($entries as $entryContent) {
            $entriesTransform[] = [
                'title' => $entryContent->get('question'),
                'content' => $entryContent->get('answer'),
            ];
        }

        return $this->render('pages/content.html.twig', [
            'entry' => $entry,
            'entries' => $entriesTransform,
        ]);
    }
}
