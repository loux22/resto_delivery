<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Member;
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
}
