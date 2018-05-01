<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;

class MapController
{
    private $twig;

    public function __construct(\Twig_Environment $twig)
    {
        $this->twig = $twig;
    }

    /**
     * @param $name
     * @return Response
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function __invoke($name)
    {
        $content = $this->twig->render(
            'base.html.twig',
            array('name' => $name)
        );

        return new Response($content);
    }

}
