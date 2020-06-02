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
        // si quelqu'un est connecté on le redirige vers la page home 
        $userLog = $this -> getUser();
        if($userLog != null){
            return $this->redirectToRoute('home');
        }
        // recupere l'url de la route
        $currentRoute = $request->attributes->get('_route');
        
        //recupere le dernier mail
        $lastUsername = $authenticationUtils -> getLastUsername();
        //recupere les erreurs
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
