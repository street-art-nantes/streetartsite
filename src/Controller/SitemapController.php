<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

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
        // TODO generate or config file
        $routes = [
            [
                'title' => '<a href="/map">Carte</a>',
                'content' => '',
            ],
            [
                'title' => '<a href="/list">Liste</a>',
                'content' => '',
            ],
            [
                'title' => '<a href="/faq">FAQ</a>',
                'content' => '',
            ],
            [
                'title' => '<a href="/sitemap">Sitemap</a>',
                'content' => '',
            ],
            [
                'title' => '<a href="/legals">Mentions légales</a>',
                'content' => '',
            ],
            [
                'title' => '<a href="/artwork/new">Soumettre une photo</a>',
                'content' => '',
            ],
        ];

        return $this->render('pages/content.html.twig', [
            'entries' => $routes,
            'pageTitle' => 'Sitemap',
        ]);
    }
}
