<?php

namespace App\Controller;

use Contentful\Delivery\Client;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class BlogController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function __invoke($id)
    {
        /** @var Client $client */
        $client = $this->get('contentful.delivery');
        $entry = $client->getEntry($id);

        if (!$entry) {
            throw new NotFoundHttpException();
        }

        return $this->render('pages/content.html.twig', [
            'blog' => $entry,
        ]);
    }
}
