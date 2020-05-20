<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserController extends AbstractController
{
    /**
     * @Route("/user", name="user")
     */
    public function index($name, \Swift_Mailer $mailer)
    {
        $message = (new \Swift_Message('Hello Email'))
            ->setFrom('funpower1238@gmail.com')
            ->setTo('louis.ardilly@ynov.com')
            ->setBody('aaaaaa');
        $mailer->send($message);

    }
}
