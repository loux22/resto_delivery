<?php

namespace App\Controller;

use App\Entity\Command;
use App\Entity\CommandDish;
use App\Entity\Dish;
use App\Entity\User;
use App\Entity\Member;
use App\Entity\Note;
use App\Entity\Restorer;
use App\Form\MemberModifyType;
use App\Form\MemberRegisterType;
use PhpParser\Node\Expr\Isset_;
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
        $userLog = $this->getUser();
        if ($userLog != null) {
            return $this->redirectToRoute('home');
        }
        $user = new User;
        $member = new Member;
        $error = "";
        $repository = $this->getDoctrine()->getRepository(User::class);
        // on recupere tout les members
        $allUser = $repository->findAll();
        $form = $this->createForm(MemberRegisterType::class, $member);
        $input = $request->request->all();
        // si on a appuyer sur le bouton s'inscrire, et on verifie si le mail est déja dans la base de données
        if (isset($input["mail"])) {
            foreach ($allUser as $key => $value) {
                if ($value->getMail() == $input['mail']) {
                    $error = "ce mail est déja dans la base de donnée";
                }
            }
            if ($error == "") {
                // Mot de passe obligatoirement >= 8 et si il est verifié
                if (strlen($input["mail"]) < 8) {
                    $this->addFlash('errors', 'Ton email n\'est pas valide');
                } else if (strlen($input["password"]) < 8) {
                    $this->addFlash('errors', 'Ton password doit contenir au moins 8 caracteres');
                } else if ($input["password"] != $input["repeat"]) {
                    $this->addFlash('errors', 'Ton password n\'est pas le meme');
                } else {
                    $form->handleRequest($request);
                    // si formulaire est valid
                    if ($form->isSubmitted() && $form->isValid()) {
                        // chiffrage du mot de passe
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
                        return $this->redirectToRoute('login');
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
        // si on n'est pas connecté on le redirige vers la page login
        $userLog = $this->getUser();
        if ($userLog == null) {
            $this->addFlash('errors', 'il faut être connecté pour accéder au profil');
            return $this->redirectToRoute('login');
        }
        if ($userLog -> getRoles()[0] != 'MEMBER') {
            $this->addFlash('errors', 'il faut être un membre pour accéder à cette page');
            return $this->redirectToRoute('home');
        }

        $repository = $this->getDoctrine()->getRepository(Member::class);
        $member = $repository->findBy([
            "user" => $userLog
        ]);
        $form = $this->createForm(MemberModifyType::class, $member[0]);
        $manager = $this->getDoctrine()->getManager();
        $form->handleRequest($request);
        // modification du profil
        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($member[0]); //commit(git)
            $manager->flush(); // push(git)
            $this->addFlash('success', 'Votre profil a été modifié');
        }

        // modification mot de passe avec verification qu'il a bien plus de 8 caracteres et est verifié
        $input = $request->request->all();
        if (isset($input["lastPassword"])) {
            if (password_verify($input["lastPassword"], $member[0]->getUser()->getPassword())) {
                if ($input["newPassword"] === $input["reapeatPassword"]) {
                    if (strlen($input["newPassword"]) >= 8) {
                        $password = $passwordEncoder->encodePassword($userLog, $input["newPassword"]);
                        $userLog->setPassword($password);
                        $manager->persist($userLog); //commit(git)
                        $manager->flush(); // push(git)
                        $this->addFlash('success', 'Le mot de passe a été modifié');
                    } else {
                        $this->addFlash('errors', 'Le mot de passe est trop court');
                    }
                } else {
                    $this->addFlash('errors', 'le mot de passe n\'est pas confirmer');
                }
            } else {
                $this->addFlash('errors', 'ce n\'est pas l\'ancien mot de passe');
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
    public function historicCommand(Request $request)
    {
        // si quelqu'un est connecté on le redirige vers la page login
        $userLog = $this->getUser();
        if ($userLog == null) {
            $this->addFlash('errors', 'il faut être connecté pour accéder a l\'historic des commandes');
            return $this->redirectToRoute('login');
        }
        if ($userLog -> getRoles()[0] != 'MEMBER') {
            $this->addFlash('errors', 'il faut être un membre pour accéder à cette page');
            return $this->redirectToRoute('home');
        }
        $repository = $this->getDoctrine()->getRepository(Member::class);
        $member = $repository->findBy([
            "user" => $userLog
        ]);
        $manager = $this->getDoctrine()->getManager();
        $repository = $this->getDoctrine()->getRepository(Command::class);
        // recuperation de toutes les commandes
        $commands = $repository->commandByUserLog($userLog);
        $commandDishRepository = $this->getDoctrine()->getRepository(CommandDish::class);
        $dishRepository = $this->getDoctrine()->getRepository(Dish::class);
        $restorerRepository = $this->getDoctrine()->getRepository(Restorer::class);
        $noteRepository = $this->getDoctrine()->getRepository(Note::class);
        $commandDish = [];
        foreach ($commands as $key => $command) {
            // pour chaque commandes on verifie si ça fais plus d'une heure que ça a été commandé
            if ($command->getDelivery() <= new \DateTime("+ 2 hours")) {
                $command->setStatus(true);
                $manager->persist($command); //commit(git)
                $manager->flush(); // push(git)
            }
            // on recupere le contenu des plats de la commande
            $commandDish[] = $commandDishRepository->findBy([
                "command" => $command
            ]);
        }
        $note = [];
        // on recupere la note que l'utilisateur a donné au plat (si il n'a pas noter ça retourne "null")
        foreach ($commandDish as $key => $dish) {
            foreach ($dish as $keys => $d) {
                $note[$key][$keys][] = $noteRepository->findBy([
                    'user' => $userLog,
                    'dish' => $d->getDish()
                ]);
            }
        }


        $input = $request->request->all();
        // pour ajouter ou modifier une note
        if (isset($input) && $input != []) {
            foreach ($commandDish as $key => $dish) {
                foreach ($dish as $key => $d) {
                    // pour ajouter une note
                    if (isset($input[strval($d->getDish()->getId())])) {
                        $note = new Note;
                        $note->setUser($userLog);
                        $note->setDish($d->getDish());
                        $note->setNote($input["note"]);
                        $manager->persist($note); //commit(git)
                        $manager->flush(); // push(git)
                        $this->addFlash('success', 'vous venez de mettre un ' . $input["note"] . ' au plat ' . $d->getDish()->getName());
                        return $this->redirectToRoute('historicCommand');
                    };
                    // si il y'a déja une note il y'a un bouton modifier et donc on modifie la note
                    if (isset($input["m" . strval($d->getDish()->getId())])) {
                        $note = $noteRepository->findOneBy([
                            'user' => $userLog,
                            'dish' => $d->getDish()->getId()
                        ]);
                        $note -> setNote($input["note"]);
                        $manager->persist($note); //commit(git)
                        $manager->flush(); // push(git)
                        $this->addFlash('success', 'vous venez de modier la note du plat ' . $d->getDish()->getName() . ' à ' . $input["note"]);
                        return $this->redirectToRoute('historicCommand');
                    }
                }
            }
        }

        return $this->render('member/historicCommand.html.twig', [
            "commands" => $commands,
            "commandDish" => $commandDish,
            "note" => $note,
            "member" => $member[0]
        ]);
    }
}
