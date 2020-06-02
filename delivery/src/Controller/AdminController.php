<?php

namespace App\Controller;

use App\Entity\Dish;
use App\Entity\Note;
use App\Entity\User;
use App\Entity\Member;
use App\Entity\Command;
use App\Entity\Restorer;
use App\Entity\CommandDish;
use App\Form\MemberModifyType;
use App\Form\RestorerRegisterType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin/dashboard", name="adminDashboard")
     */
    public function adminDashboard()
    {
        $userLog = $this->getUser();
        if ($userLog === null) {
            $this->addFlash('errors', 'il faut être connecté en tant qu\'admin\' pour acceder au dashboard');
            return $this->redirectToRoute('home');
        }
        if ($userLog->getRoles()[0] != 'ADMIN') {
            $this->addFlash('errors', 'il faut être connecté en tant qu\'admin\' pour acceder au dashboard');
            return $this->redirectToRoute('home');
        }
        $repository = $this->getDoctrine()->getRepository(Restorer::class);
        // on recupere le nombre de resto
        $nbRestorer = $repository->findAll();

        $repoCommand = $this->getDoctrine()->getRepository(Command::class);
        // on recupere le nombre de commande
        $nbCommand = $repoCommand->findAll();
        // on recupere le nombre de commande en cours
        $nbCommandInProgress = $repoCommand->findBy([
            "status" => 0
        ]);

        return $this->render('admin/dashboard.html.twig', [
            "nbRestorer" => count($nbRestorer),
            "nbCommand" => count($nbCommand),
            "nbCommandInProgress" => count($nbCommandInProgress)
        ]);
    }


    /**
     * @Route("/admin/restaurents", name="adminRestorer")
     */
    public function adminRestorer()
    {
        $userLog = $this->getUser();
        if ($userLog === null) {
            $this->addFlash('errors', 'il faut être connecté en tant qu\'admin pour acceder au dashboard');
            return $this->redirectToRoute('home');
        }
        if ($userLog->getRoles()[0] != 'ADMIN') {
            $this->addFlash('errors', 'il faut être connecté en tant qu\'admin pour acceder au dashboard');
            return $this->redirectToRoute('home');
        }
        $repository = $this->getDoctrine()->getRepository(Restorer::class);
        // on recupere tout les restos
        $allRestorer = $repository->findAll();
        return $this->render('admin/restorers.html.twig', [
            'allRestorer' => $allRestorer
        ]);
    }


    /**
     * @Route("/admin/restaurent/modify/{id}", name="adminModifyRestorer")
     */
    public function adminModifyRestorer($id, Request $request)
    {
        $userLog = $this->getUser();
        if ($userLog === null) {
            $this->addFlash('errors', 'il faut être connecté en tant qu\'admin pour acceder au dashboard');
            return $this->redirectToRoute('home');
        }
        if ($userLog->getRoles()[0] != 'ADMIN') {
            $this->addFlash('errors', 'il faut être connecté en tant qu\'admin pour acceder au dashboard');
            return $this->redirectToRoute('home');
        }
        $repository = $this->getDoctrine()->getRepository(Restorer::class);
        $restorer = $repository->find($id);

        $form = $this->createForm(RestorerRegisterType::class, $restorer);
        $form->handleRequest($request);
        // modifie le restaurent
        if ($form->isSubmitted() && $form->isValid()) {
            if ($restorer->getFile()) {
                $restorer->removeFile($restorer->getId());
                $restorer->fileUpload($restorer->getId());
            }
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($restorer); //commit(git)
            $manager->flush(); // push(git)
            $this->addFlash('success', 'le plat ' . $restorer->getName() . ' a bien été modifié');
            return $this->redirectToRoute('adminRestorer');
        }
        return $this->render('admin/modifyRestorers.html.twig', [
            'restorer' => $restorer,
            "restorerFormModify" => $form->createView()
        ]);
    }


    /**
     * @Route("/admin/restaurent/remove/{id}", name="adminRemoveRestorer")
     */
    public function adminRemoveRestorer($id, Request $request)
    {
        $userLog = $this->getUser();
        if ($userLog === null) {
            $this->addFlash('errors', 'il faut être connecté en tant qu\'admin pour acceder au dashboard');
            return $this->redirectToRoute('home');
        }
        if ($userLog->getRoles()[0] != 'ADMIN') {
            $this->addFlash('errors', 'il faut être connecté en tant qu\'admin pour acceder au dashboard');
            return $this->redirectToRoute('home');
        }
        $manager = $this->getDoctrine()->getManager();
        $repository = $this->getDoctrine()->getRepository(Restorer::class);
        $restorer = $repository->find($id);
        $restorerName = $restorer->getName();
        $repoNote = $this->getDoctrine()->getRepository(Note::class);
        $repoCommandDish = $this->getDoctrine()->getRepository(CommandDish::class);
        $repoDish = $this->getDoctrine()->getRepository(Dish::class);
        // on recupere tout les plats du resto
        $dishRestorer = $repoDish->findBy([
            'restorer' => $restorer
        ]);
        
        // on recupere toutes les note des plats du resto ainsi que le contenu des commandes
        $note = [];
        $command = [];
        foreach ($dishRestorer as $key => $dish) {
            $note[] = $repoNote->findBy([
                "dish" => $dish
            ]);
            $command[] = $repoCommandDish->findBy([
                'dish' => $dish
            ]);
        }
        // on supprime note et commande
        if (!empty($note)) {
            foreach ($note as $key => $n) {
                if (!empty($n)) {
                    foreach ($n as $key => $value) {
                        $manager->remove($value);
                    }
                }
            }
        }

        if (!empty($command)) {
            foreach ($command as $key => $c) {
                if (!empty($c)) {
                    foreach ($c as $key => $value) {
                        $manager->remove($value);
                    }
                }
            }
        }

        foreach ($dishRestorer as $key => $dish) {
            $manager->remove($dish);
        }
        $manager->remove($restorer);
        $manager->flush();

        $this->addFlash('success', 'le plat ' . $restorerName . ' a bien été modifié');
        return $this->redirectToRoute('adminRestorer');
    }


    /**
     * @Route("/admin/restaurent/command/{id}", name="adminRestorerCommand")
     */
    public function adminRestorerCommand($id)
    {
        $userLog = $this->getUser();
        if ($userLog === null) {
            $this->addFlash('errors', 'il faut être connecté en tant qu\'admin pour acceder au dashboard');
            return $this->redirectToRoute('home');
        }
        if ($userLog->getRoles()[0] != 'ADMIN') {
            $this->addFlash('errors', 'il faut être connecté en tant qu\'admin pour acceder au dashboard');
            return $this->redirectToRoute('home');
        }
        $repository = $this->getDoctrine()->getRepository(Restorer::class);
        $restorer = $repository->find($id);
        $repoCommandDish = $this->getDoctrine()->getRepository(CommandDish::class);
        $repoCommand = $this->getDoctrine()->getRepository(Command::class);
        $repoNote = $this->getDoctrine()->getRepository(Note::class);
        $repoDish = $this->getDoctrine()->getRepository(Dish::class);
        $repoUser = $this->getDoctrine()->getRepository(User::class);
        $repoMember = $this->getDoctrine()->getRepository(Member::class);

        // recupere tout les plats commander au restaurent
        $dishCommands = $repoCommandDish->findCommandDishAdmin($restorer);

        // on recupere le contenu des commandes du restaurent
        $commandRestorer = [];
        if (count($commandRestorer) == 0 && !empty($dishCommands)) {
            $commandRestorer[] = $dishCommands[0];
        }
        $status = false;
        foreach ($dishCommands as $keys => $value) {
            $status = false;
            foreach ($commandRestorer as $key => $command) {
                if ($command->getCommand()->getId() == $value->getCommand()->getId()) {
                    $status = true;
                }
            }
            if ($status == false) {
                $commandRestorer[] = $value;
            }
        }
        // dans [0] on stocke la commande, [1] le contenu, [2] les plats, [3] le membre qui a commandé
        $command = [];
        foreach ($commandRestorer as $key => $value) {
            $command[$key][0][] = $repoCommand->findOneBy([
                "id" => $value->getCommand()->getId()
            ]);
            $command[$key][1][] = $repoCommandDish->findBy([
                "command" => $command[$key][0][0]->getId()
            ]);
            foreach ($command[$key][1] as $keys => $v) {
                foreach ($v as $k => $dish) {
                    $command[$key][2][] = $repoDish->findOneBy([
                        "id" => $dish->getDish()->getId()
                    ]);
                }
            }
            foreach ($command[$key][0] as $count => $user) {
                $command[$key][3][] = $repoUser->findOneBy([
                    "id" => $user->getUser()->getId()
                ]);
            }
        }

        $dish = [];
        if (count($dish) == 0 && !empty($dishCommands)) {
            $dish[] = $dishCommands[0];
        }
        $status = false;
        foreach ($dishCommands as $keys => $v) {
            $status = false;
            foreach ($dish as $key => $c) {
                if ($c->getDish()->getId() == $v->getDish()->getId()) {
                    $status = true;
                }
            }
            if ($status == false) {
                $dish[] = $v;
            }
        }
        // on recupere la note moyenne de chaque plats
        $note = [];
        // dd($dish);
        foreach ($dish as $key => $value) {
            $note[$key][0] = $value->getDish();
            $note[$key][1] = $repoNote->dishNote($value->getDish());
        }

        return $this->render('admin/restorerCommand.html.twig', [
            'restorer' => $restorer,
            'command' => $command,
            'note' => $note
        ]);
    }


    /**
     * @Route("/admin/members", name="adminMembers")
     */
    public function adminMembers()
    {
        $userLog = $this->getUser();
        if ($userLog === null) {
            $this->addFlash('errors', 'il faut être connecté en tant qu\'admin pour acceder au dashboard');
            return $this->redirectToRoute('home');
        }
        if ($userLog->getRoles()[0] != 'ADMIN') {
            $this->addFlash('errors', 'il faut être connecté en tant qu\'admin pour acceder au dashboard');
            return $this->redirectToRoute('home');
        }

        $repository = $this->getDoctrine()->getRepository(Member::class);
        $repoUser = $this->getDoctrine()->getRepository(User::class);
        // on recupere tous les membres
        $userMembers = $repository->findAll();
        $members = [];
        foreach ($userMembers as $key => $member) {
            $members[$key][0][] = $member;
            $members[$key][1][] = $repoUser->findOneBy([
                'id' => $member->getUser()
            ]);
        }

        return $this->render('admin/members.html.twig', [
            'members' => $members
        ]);
    }

    /**
     * @Route("/admin/member/modify/{id}", name="adminMemberModify")
     */
    public function adminMemberModify($id, Request $request)
    {
        $userLog = $this->getUser();
        if ($userLog === null) {
            $this->addFlash('errors', 'il faut être connecté en tant qu\'admin pour acceder au dashboard');
            return $this->redirectToRoute('home');
        }
        if ($userLog->getRoles()[0] != 'ADMIN') {
            $this->addFlash('errors', 'il faut être connecté en tant qu\'admin pour acceder au dashboard');
            return $this->redirectToRoute('home');
        }
        $repository = $this->getDoctrine()->getRepository(Member::class);
        $member = $repository -> find($id);
        $form = $this->createForm(MemberModifyType::class, $member);
        $manager = $this->getDoctrine()->getManager();
        $form->handleRequest($request);
        // permet de modifier le profil
        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($member); //commit(git)
            $manager->flush(); // push(git)
            $this->addFlash('success', 'le profil ' . $member -> getusername() . ' a bien été modifier');
            return $this->redirectToRoute('adminMembers');
        }

        return $this->render('admin/memberModify.html.twig', [
            'member' => $member,
            "memberFormModify" => $form->createView(),
        ]);
    }


    /**
     * @Route("/admin/member/remove/{id}", name="adminMemberRemove")
     */
    public function adminMemberRemove($id, Request $request)
    {
        $userLog = $this->getUser();
        if ($userLog === null) {
            $this->addFlash('errors', 'il faut être connecté en tant qu\'admin pour acceder au dashboard');
            return $this->redirectToRoute('home');
        }
        if ($userLog->getRoles()[0] != 'ADMIN') {
            $this->addFlash('errors', 'il faut être connecté en tant qu\'admin pour acceder au dashboard');
            return $this->redirectToRoute('home');
        }
        $manager = $this->getDoctrine()->getManager();
        $repository = $this->getDoctrine()->getRepository(Member::class);
        $repoNote = $this->getDoctrine()->getRepository(Note::class);
        $repoUser = $this->getDoctrine()->getRepository(User::class);
        $repoCommandDish = $this->getDoctrine()->getRepository(CommandDish::class);
        $repoCommand = $this->getDoctrine()->getRepository(Command::class);
        $member = $repository -> find($id);
        $user = $repoUser -> findOneBy([
            "id" => $member -> getUser()
        ]);
        // on recupere toutes les notes du user et on les supprime
        $note = $repoNote -> findBy([
            'user' => $user
        ]);
        foreach ($note as $key => $value) {
            $manager->remove($value);
        }
        // on recupere toutes les commandes du user et on les supprime
        $command = $repoCommand -> findBy([
            'user' => $user
        ]);
        $commandDish = [];
        foreach ($command as $key => $value) {
            $commandDish[] = $repoCommandDish -> findBy([
                'command' => $value -> getId()
            ]);
        }
        foreach ($commandDish as $key => $com) {
            foreach ($com as $keys => $c) {
                $manager->remove($c);
            }
        }
        foreach ($command as $key => $value) {
            $manager->remove($value);
        }
        $memberName = $member -> getUsername();
        // on supprime le user
        $manager->remove($member);
        $manager->remove($user);
        $manager->flush();
        $this->addFlash('success', 'le profil ' . $memberName . ' a bien été supprimé');
        return $this->redirectToRoute('adminMembers');
    }

     /**
     * @Route("/admin/member/{id}/command", name="adminMemberCommand")
     */
    public function adminMemberCommand($id)
    {
        $userLog = $this->getUser();
        if ($userLog === null) {
            $this->addFlash('errors', 'il faut être connecté en tant qu\'admin pour acceder au dashboard');
            return $this->redirectToRoute('home');
        }
        if ($userLog->getRoles()[0] != 'ADMIN') {
            $this->addFlash('errors', 'il faut être connecté en tant qu\'admin pour acceder au dashboard');
            return $this->redirectToRoute('home');
        }
        $repository = $this->getDoctrine()->getRepository(Member::class);
        $repoUser = $this->getDoctrine()->getRepository(User::class);
        $member = $repository -> find($id);
        $user = $repoUser -> findOneBy([
            "id" => $member -> getUser()
        ]);
        $repoCommand = $this->getDoctrine()->getRepository(Command::class);
        $repoCommandDish = $this->getDoctrine()->getRepository(CommandDish::class);
        $repoDish = $this->getDoctrine()->getRepository(Dish::class);
        // On recupere les commandes du member ainsi que le contenue
        $allCommands = $repoCommand -> findBy([
            'user' => $user
        ]);
        $commands = [];
        foreach ($allCommands as $key => $command) {
            $commands[$key][0][] = $command;
            $commandDish = $repoCommandDish -> findBy([
                "command" => $command
            ]);
            foreach ($commandDish as $keys => $dish) {
                $commands[$key][1][] = $repoDish -> findBy([
                    'id' => $dish -> getDish() -> getId()
                ]);
            }
        }
        return $this->render('admin/memberCommand.html.twig', [
            'member' => $member,
            'commands' => $commands
        ]);
    }
}
