<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class UserController extends AbstractController
{
    /**
     * @Route("/login", name="login")
     * @Route("/login/restaurent", name="loginRestaurent")
     * @Route("/login/admin", name="loginAdmin")
     */
    public function login(AuthenticationUtils $authenticationUtils, Request $request)
    {
        $currentRoute = $request->attributes->get('_route');
        $lastUsername = $authenticationUtils -> getLastUsername();
        $error = $authenticationUtils->getLastAuthenticationError();

        if($error){
            $this -> addFlash('errors', 'erreur d\'authentification');
        }
        return $this->render('user/login.html.twig', [
            'lastUsername' => $lastUsername,
            'currentRoute' => $currentRoute
        ]);
    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logout()
    {
    }
}
