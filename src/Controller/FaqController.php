<?php

namespace App\Controller;

use Contentful\Delivery\Client;
use Contentful\Delivery\Query;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

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
        $query = new Query();
        $query->setContentType('faq');
        $entries = $client->getEntries($query);

        $entriesTransform = [];
        foreach ($entries as $entryContent) {
            $entriesTransform[] = [
                'title' => $entryContent->get('question'),
                'content' => $entryContent->get('answer'),
            ];
        }

        return $this->render('pages/content.html.twig', [
            'entries' => $entriesTransform,
            'pageTitle' => 'FAQ',
        ]);
    }
}
