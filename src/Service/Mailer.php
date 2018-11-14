<?php

namespace App\Service;

use FOS\UserBundle\Mailer\Mailer as BaseMailer;
use FOS\UserBundle\Model\UserInterface;

class Mailer extends BaseMailer
{
    /**
     * @param UserInterface $user
     */
    public function sendWelcomeEmailMessage(UserInterface $user)
    {
        $template = 'FOSUSerBundle:Registration:email.txt.twig';
//        $url = $this->router->generate('** custom login path**', array(), true);
        $rendered = $this->templating->render($template, [
            'user' => $user,
            'password' => $user->getPlainPassword(),
        ]);
        $this->sendEmailMessage($rendered,
            $this->parameters['from_email']['confirmation'], $user->getEmail());
    }
}
