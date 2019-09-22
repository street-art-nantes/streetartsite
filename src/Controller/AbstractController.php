<?php

namespace App\Controller;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController as BaseAbstractController;


/**
 * Class AbstractController
 * @package App\Controller
 */
abstract class AbstractController extends BaseAbstractController
{
    /**
     * @return User|null
     */
    protected function getUser() {
        return $this->getUser();
    }
}
