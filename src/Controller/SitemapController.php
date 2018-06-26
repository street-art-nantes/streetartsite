<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class SitemapController.
 */
class SitemapController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function __invoke()
    {
        $client = $this->get('contentful.delivery');
        $entry = $client->getEntry('5o9QHWZhTyouo2oIiGEOkw');

        if (!$entry) {
            throw new NotFoundHttpException();
        }

        return $this->render('pages/content.html.twig', [
            'entry' => $entry,
        ]);
    }
}
