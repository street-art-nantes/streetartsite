<?php

namespace App\Controller;

use Contentful\Delivery\Client;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class ReleasesController.
 */
class ReleasesController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function __invoke()
    {
        /** @var Client $client */
        $client = $this->get('contentful.delivery');
        $entry = $client->getEntry('5I57qW3gTSAEogWCKm8iIQ');

        if (!$entry) {
            throw new NotFoundHttpException();
        }

        return $this->render('pages/content.html.twig', [
            'blog' => $entry,
            'pageTitle' => 'Releases',
        ]);
    }
}
