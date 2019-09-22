<?php

namespace App\Controller\API;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class MeController.
 */
class MeController extends AbstractController
{
    /**
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function __invoke(Request $request)
    {
        $request->attributes->add(['_resource' => 'User']);

        return $this->forward('Api', ['request' => $request, 'id' => $this->getUser()->getId()]);
    }
}
