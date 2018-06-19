<?php

namespace App\Controller;

use App\Entity\Poi;
use Contentful\Delivery\Query;
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
        $client = $this->get('contentful.delivery');
        $entry = $client->getEntry('36gXCzBGjCCgog8aYqeaoK');
        $query = new Query();
        $query->setContentType('faq');
        $entries = $client->getEntries($query);

        if (!$entry) {
            throw new NotFoundHttpException();
        }

        return $this->render('pages/content.html.twig', [
            'entry' => $entry,
            'entries' => $entries,
        ]);
    }
}
