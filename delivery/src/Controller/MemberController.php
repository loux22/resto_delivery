<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Member;
use App\Form\MemberModifyType;
use App\Form\MemberRegisterType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class MemberController extends AbstractController
{
    /**
     * @Route("/register/member", name="registerMember")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        // si quelqu'un est connecté on le redirige vers la page home 
        $userLog = $this -> getUser();
        if($userLog != null){
            return $this->redirectToRoute('home');
        }
        $user = new User;
        $member = new Member;
        $error = "";
        $repository = $this->getDoctrine()->getRepository(User::class);
        $allUser = $repository->findAll();
        $form = $this->createForm(MemberRegisterType::class, $member);
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
                        $user->setRoles(['MEMBER']);
                        $manager = $this->getDoctrine()->getManager();
                        $manager->persist($user); //commit(git)
                        $manager->flush(); // push(git)

                        $member->setSold(0);
                        $member->setUser($user);
                        $manager->persist($member); //commit(git)
                        $manager->flush(); // push(git)
                        $this->addFlash('success', 'Vous êtes inscris');
                    }
                }
            }
        }

        return $this->render('member/register.html.twig', [
            'memberForm' => $form->createView(),
            'error' => $error
        ]);
    }

    /**
     * @Route("/profil", name="profil")
     */
    public function profil(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        // si quelqu'un est connecté on le redirige vers la page login
        $userLog = $this -> getUser();
        if($userLog == null){
            $this->addFlash('errors', 'il faut être connecté pour accéder au profil');
            return $this->redirectToRoute('login');
        }

        $repository = $this->getDoctrine()->getRepository(Member::class);
        $member = $repository->findBy([
            "user" => $userLog
        ]);
        $form = $this->createForm(MemberModifyType::class, $member[0]);
        $manager = $this-> getDoctrine() -> getManager();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager -> persist($member[0]); //commit(git)
            $manager -> flush(); // push(git)
        }

        $input = $request->request->all();
        if (isset($input["lastPassword"])) {
            if (password_verify($input["lastPassword"], $member[0] ->getUser() -> getPassword())) {
                if ($input["newPassword"] === $input["reapeatPassword"]) {
                    if (strlen($input["newPassword"]) >= 8) {
                        $password = $passwordEncoder->encodePassword($userLog, $input["newPassword"]);
                        $userLog -> setPassword($password);
                        $manager -> persist($userLog); //commit(git)
                        $manager -> flush(); // push(git)
                        $this -> addFlash('success','Le mot de passe a été modifié');
                    }else{
                        $this -> addFlash('errors','Le mot de passe est trop court');
                    }
                }else{
                    $this -> addFlash('errors','le mot de passe n\'est pas confirmer');
                }
            }else{
                $this -> addFlash('errors','ce n\'est pas l\'ancien mot de passe' );
            }
        }

        return $this->render('member/profil.html.twig', [
            "member" => $member[0],
            "memberFormModify" => $form->createView(),
        ]);
    }


    /**
     * @Route("/historic/command", name="historicCommand")
     */
    public function historicCommand()
    {
        // si quelqu'un est connecté on le redirige vers la page login
        $userLog = $this -> getUser();
        if($userLog == null){
            $this->addFlash('errors', 'il faut être connecté pour accéder au profil');
            return $this->redirectToRoute('login');
        }

        return $this->render('member/historicCommand.html.twig', [

        ]);
    }
}
