<?php

namespace App\Controller\API;

use App\Entity\User;
use App\Controller\AbstractController;
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

        /** @var User $user */
        $user = $this->getUser();
        return $this->forward('Api', ['request' => $request, 'id' => $user->getId()]);
    }
}
