<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Restorer;
use App\Form\RestorerRegisterType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RestorerController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index()
    {
        
        return $this->render('restorer/index.html.twig', [

        ]);
    }
    
    /**
     * @Route("/register/restorer", name="registerRestorer")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        $user = new User;
        $restorer = new Restorer;
        $error = "";
        $repository = $this->getDoctrine()->getRepository(User::class);
        $allUser = $repository->findAll();
        $repository = $this->getDoctrine()->getRepository(Restorer::class);
        $lastRestorer = $repository->findAll();
        $lastRestorer = end($lastRestorer);
        $form = $this->createForm(RestorerRegisterType::class, $restorer);
        $input = $request->request->all();

        if (isset($input["mail"])) {
            foreach ($allUser as $key => $value) {
                if ($value->getMail() == $input['mail']) {
                    $error = "ce mail est déja dans la base de donnée";
                }
            }
            if ($error == "") {
                if (strlen($input["mail"]) < 8) {
                    $this->addFlash('errors', 'Ton email n\'est pas valide');
                } else if (strlen($input["password"]) < 8) {
                    $this->addFlash('errors', 'Ton password doit contenir au moins 8 caracteres');
                } else if ($input["password"] != $input["repeat"]) {
                    $this->addFlash('errors', 'Ton password n\'est pas le meme');
                } else {
                    $form->handleRequest($request);

                    if ($form->isSubmitted() && $form->isValid()) {
                        $password = $passwordEncoder->encodePassword($user, $input["password"]);
                        $user->setPassword($password);
                        $user->setMail($input["mail"]);
                        $user->setRoles(['RESTORER']);
                        $manager = $this->getDoctrine()->getManager();
                        $manager->persist($user); //commit(git)
                        $manager->flush(); // push(git)
                        if ($lastRestorer != false) {
                            $restorer->fileUpload($lastRestorer -> getId() + 1);
                        }else {
                            $restorer->fileUpload(1);
                        }
                        
                        $restorer->setUser($user);
                        $manager->persist($restorer); //commit(git)
                        $manager->flush(); // push(git)
                        $this->addFlash('success', 'Vous êtes inscris');
                    }
                }
            }
        }
        return $this->render('restorer/register.html.twig', [
            'restorerForm' => $form->createView(),
            'error' => $error
        ]);
    }
}
