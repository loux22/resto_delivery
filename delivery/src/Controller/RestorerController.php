<?php

namespace App\Controller;

use App\Entity\Dish;
use App\Entity\Note;
use App\Entity\User;
use App\Entity\Member;
use App\Entity\Command;
use App\Entity\Restorer;
use App\Form\AddDishType;
use App\Entity\CommandDish;
use App\Form\RestorerRegisterType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
        $userLog = $this->getUser();
        if ($userLog != null) {
            if ($userLog -> getRoles()[0] === 'RESTORER') {
                return $this->redirectToRoute('restorerDashboard');
            } elseif ($userLog -> getRoles()[0] === 'ADMIN') {
                return $this->redirectToRoute('adminDashboard');
            }
        }
        $repository = $this->getDoctrine()->getRepository(Member::class);
        $member = $repository->findOneBy([
            "user" => $userLog
        ]);
        if ($userLog === null) {
            $member = "";
        }
        $repository = $this->getDoctrine()->getRepository(Restorer::class);
        // recuperation de tous les restaurents
        $allRestorers = $repository->findAll();
        $repoNote = $this->getDoctrine()->getRepository(Note::class);
        $restorers = [];
        // recuperation de la note moyenne des plats du restaurent, ce qui donne la note du restaurent
        foreach ($allRestorers as $key => $restorer) {
            $restorers[$key][0] = $restorer;
            $restorers[$key][1] = $repoNote->dishNoteRestaurent($restorer);
        }
        // on affiche que 6 resto aleatoirement
        $rand = rand(1, (count($restorers)) - 6);
        return $this->render('restorer/index.html.twig', [
            "restorers" => $restorers,
            "rand" => $rand,
            'member' => $member
        ]);
    }

    public function restorer()
    {
        $userLog = $this->getUser();
        if ($userLog === null) {
            $this->addFlash('errors', 'il faut être connecté en tant que restaurent pour acceder au dashboard');
            return $this->redirectToRoute('home');
        }else{
            if ($userLog->getRoles()[0] != 'RESTORER') {
                $this->addFlash('errors', 'il faut être connecté en tant que restaurent pour acceder au dashboard');
                return $this->redirectToRoute('home');
            }
            $repository = $this->getDoctrine()->getRepository(Restorer::class);
            $restorer = $repository->findOneBy([
                "user" => $userLog->getId()
            ]);
            return $restorer;
        }
        
    }

    /**
     * @Route("/restaurent/dashboard", name="restorerDashboard")
     */
    public function restorerDashboard()
    {
        $userLog = $this->getUser();
        if ($userLog === null) {
            $this->addFlash('errors', 'il faut être connecté en tant que restaurent pour acceder au dashboard');
            return $this->redirectToRoute('home');
        }
        if ($userLog->getRoles()[0] != 'RESTORER') {
            $this->addFlash('errors', 'il faut être connecté en tant que restaurent pour acceder au dashboard');
            return $this->redirectToRoute('home');
        }
        $repository = $this->getDoctrine()->getRepository(Restorer::class);
        // on recupere le resto
        $restorer = $repository->findOneBy([
            "user" => $userLog->getId()
        ]);
        $repoDish = $this->getDoctrine()->getRepository(Dish::class);
        // on recupere les plats du resto
        $dishs = $repoDish->findBy([
            "restorer" => $restorer
        ]);
        $repoCommandDish = $this->getDoctrine()->getRepository(CommandDish::class);
        $commandInProgress = $this->getDoctrine()->getRepository(Command::class);
        // on recupere tout les plats commander soit le contenu des commandes 
        $commandDish = $repoCommandDish->findCommandDish($restorer);
        $commandRestorer = [];
        // si la liste $commandRestorer est vide on ajoute le premier contenu de commande
        if (count($commandRestorer) == 0 && !empty($commandDish)) {
            $commandRestorer[] = $commandDish[0];
        }
        // on garde seulement un contenu de plat par commande et pas plusieurs et on connait le nombre de commande du resto
        $status = false;
        foreach ($commandDish as $keys => $value) {
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
        $commandRestorerInProgress = [];
        // on recupere les commandes en cours du resto
        foreach ($commandRestorer as $key => $value) {
            $commandRestorerInProgress[] = $commandInProgress->findBy([
                'id' => $value->getCommand()->getId(),
                'status' => false
            ]);
            if ($commandRestorerInProgress[$key] == []) {
                unset($commandRestorerInProgress[$key]);
            }
        }

        $repoNote = $this->getDoctrine()->getRepository(Note::class);
        // on recupere la note moyenne du resto
        $note = $repoNote->dishNoteRestaurent($restorer);

        // on recupere les plats commander en cours
        $dishInProgress = [];
        foreach ($commandRestorerInProgress as $key => $value) {
            foreach ($commandDish as $key => $dish) {
                if ($value[0] == $dish->getCommand()) {
                    $dishInProgress[] = $dish;
                }
            }
        }

        // on recupere les gains du resto soit le prix des plats sans les 2,5€
        $earning = 0;
        foreach ($commandRestorer as $key => $value) {
            $com = $commandInProgress -> findOneBy([
                "id" => $value -> getCommand()
            ]);
            $earning += $com->getPrice() - 2.5;
        }



        return $this->render('restorer/dashboard.html.twig', [
            "restorer" => $restorer,
            "dishs" => $dishs,
            "commandRestorer" => $commandRestorer,
            'commandRestorerInProgress' => $commandRestorerInProgress,
            'note' => $note[0],
            'commandDish' => $commandDish,
            'dishInProgress' => $dishInProgress,
            'earning' => $earning
        ]);
    }


    /**
     * @Route("/restaurent/dishs", name="restorerDishs")
     */
    public function restorerDishs(Request $request)
    {
        $userLog = $this->getUser();
        if ($userLog === null) {
            $this->addFlash('errors', 'il faut être connecté en tant que restaurent pour acceder à la liste des plats');
            return $this->redirectToRoute('home');
        }
        if ($userLog->getRoles()[0] != 'RESTORER') {
            $this->addFlash('errors', 'il faut être connecté en tant que restaurent pour acceder à la liste des plats');
            return $this->redirectToRoute('home');
        }
        $repository = $this->getDoctrine()->getRepository(Restorer::class);
        $restorer = $repository->findOneBy([
            "user" => $userLog->getId()
        ]);
        $repoNote = $this->getDoctrine()->getRepository(Note::class);
        $repoDish = $this->getDoctrine()->getRepository(Dish::class);
        $allDishs = $repoDish->findAll();
        // on recupere tous les plats du resto
        $dishs = $repoDish->findBy([
            "restorer" => $restorer
        ]);
        // on recupere toutes les notes des plats du resto
        $note = [];
        foreach ($dishs as $key => $dish) {
            $note[] = $repoNote->dishNote($dish);
        }
        $dish = new Dish;
        $form = $this->createForm(AddDishType::class, $dish);

        $form->handleRequest($request);
        // permet d'ajouter un plat
        if ($form->isSubmitted() && $form->isValid()) {
            $dish->fileUpload(end($allDishs)->getId() + 1);
            $dish->setRestorer($restorer);
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($dish); //commit(git)
            $manager->flush(); // push(git)
            $dishs = $repoDish->findBy([
                "restorer" => $restorer
            ]);
            $this->addFlash('success', 'Votre plat a bien été ajouté');
            return $this->redirectToRoute('restorerDishs');
        }
        return $this->render('restorer/restorerDishs.html.twig', [
            'restorer' => $restorer,
            'dishs' => $dishs,
            'note' => $note,
            'formAddDish' => $form->createView()
        ]);
    }


    /**
     * @Route("/restaurent/dish/modify/{id}", name="dishModify")
     */
    public function modifyDish($id, Request $request)
    {
        $userLog = $this->getUser();
        if ($userLog === null) {
            $this->addFlash('errors', 'il faut être connecté en tant que restaurent pour acceder pour modifier un plat');
            return $this->redirectToRoute('home');
        }
        if ($userLog->getRoles()[0] != 'RESTORER') {
            $this->addFlash('errors', 'il faut être connecté en tant que restaurent pour acceder pour modifier un plat');
            return $this->redirectToRoute('home');
        }
        $repository = $this->getDoctrine()->getRepository(Restorer::class);
        $restorer = $repository->findOneBy([
            "user" => $userLog->getId()
        ]);
        $repoDish = $this->getDoctrine()->getRepository(Dish::class);
        // on recupere tout les plats du resto
        $dish = $repoDish->findOneBy([
            "id" => $id
        ]);
        $form = $this->createForm(AddDishType::class, $dish);
        $form->handleRequest($request);
        // permet de modifier un plat du resto
        if ($form->isSubmitted() && $form->isValid()) {
            if ($dish->getFile()) {
                $dish->removeFile($id);
                $dish->fileUpload($id);
            }
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($dish); //commit(git)
            $manager->flush(); // push(git)
            $this->addFlash('success', 'Votre plat ' . $dish->getName() . ' a bien été modifié');
            return $this->redirectToRoute('restorerDishs');
        }
        return $this->render('restorer/modifyDish.html.twig', [
            'formModifyDish' => $form->createView(),
            'restorer' => $restorer,
            'dish' => $dish
        ]);
    }


    /**
     * @Route("/restaurent/dish/remove/{id}", name="dishRemove")
     */
    public function removeDish($id)
    {
        $repoDish = $this->getDoctrine()->getRepository(Dish::class);
        // recupere tout les plats
        $dish = $repoDish->findOneBy([
            "id" => $id
        ]);
        // il faut supprimer toute les clé etrangeres des autres tables
        // recupere toutes les notes du plat
        $repoNote = $this->getDoctrine()->getRepository(Note::class);
        $note = $repoNote->findBy([
            "dish" => $dish
        ]);
        $manager = $this->getDoctrine()->getManager();
        // si le plat a des notes on supprime
        if (!empty($note)) {
            foreach ($note as $key => $value) {
                $manager->remove($value);
            }
        }
        $repoCommandDish = $this->getDoctrine()->getRepository(CommandDish::class);
        // on recupere tout le contenu des plats des commandes
        $commandDish = $repoCommandDish->findBy([
            "dish" => $dish
        ]);
        // si il n'y en a pas on supprime
        if (!empty($commandDish)) {
            foreach ($commandDish as $key => $value) {
                $manager->remove($value);
            }
        }
        $dishName = $dish->getName();
        // on supprime le plat
        $manager->remove($dish);
        $manager->flush();
        $this->addFlash('success', 'Votre plat ' . $dishName . ' a bien été supprimé');
        return $this->redirectToRoute('restorerDishs');
    }

    /**
     * @Route("/restaurent/profil", name="profilRestorer")
     */
    public function profilRestorer(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        $userLog = $this->getUser();
        if ($userLog === null) {
            $this->addFlash('errors', 'il faut être connecté en tant que restaurent pour acceder au profil');
            return $this->redirectToRoute('home');
        }
        if ($userLog->getRoles()[0] != 'RESTORER') {
            $this->addFlash('errors', 'il faut être connecté en tant que restaurent pour acceder au profil');
            return $this->redirectToRoute('home');
        }
        $repository = $this->getDoctrine()->getRepository(Restorer::class);
        $restorer = $repository->findOneBy([
            "user" => $userLog->getId()
        ]);
        $manager = $this->getDoctrine()->getManager();
        // permet de modifier le password
        $input = $request->request->all();
        if (isset($input["lastPassword"])) {
            if (password_verify($input["lastPassword"], $restorer->getUser()->getPassword())) {
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

        $form = $this->createForm(RestorerRegisterType::class, $restorer);
        $form->handleRequest($request);
        // permet de modifier le profil
        if ($form->isSubmitted() && $form->isValid()) {
            if ($restorer->getFile()) {
                $restorer->removeFile($restorer -> getId());
                $restorer->fileUpload($restorer -> getId());
            }
            $manager->persist($restorer); //commit(git)
            $manager->flush(); // push(git)
            $this->addFlash('success', 'Votre profil a bien été modifié');
        }
        return $this->render('restorer/profil.html.twig', [
            'restorer' => $restorer,
            "restorerFormModify" => $form->createView(),
        ]);
    }


    /**
     * @Route("/register/restorer", name="registerRestorer")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        // si quelqu'un est connecté on le redirige vers la page home 
        $userLog = $this->getUser();
        if ($userLog != null) {
            return $this->redirectToRoute('home');
        }
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
        // verification si le mail n'est pas déja dans la base de donnée
        if (isset($input["mail"])) {
            foreach ($allUser as $key => $value) {
                if ($value->getMail() == $input['mail']) {
                    $error = "ce mail est déja dans la base de donnée";
                }
            }
            if ($error == "") {
                // password avec au moins 8 caractere et verification
                if (strlen($input["mail"]) < 8) {
                    $this->addFlash('errors', 'Ton email n\'est pas valide');
                } else if (strlen($input["password"]) < 8) {
                    $this->addFlash('errors', 'Ton password doit contenir au moins 8 caracteres');
                } else if ($input["password"] != $input["repeat"]) {
                    $this->addFlash('errors', 'Ton password n\'est pas le meme');
                } else {
                    $form->handleRequest($request);
                    // permet de s'inscrire
                    if ($form->isSubmitted() && $form->isValid()) {
                        $password = $passwordEncoder->encodePassword($user, $input["password"]);
                        $user->setPassword($password);
                        $user->setMail($input["mail"]);
                        $user->setRoles(['RESTORER']);
                        $manager = $this->getDoctrine()->getManager();
                        $manager->persist($user); //commit(git)
                        $manager->flush(); // push(git)
                        if ($lastRestorer != false) {
                            $restorer->fileUpload($lastRestorer->getId() + 1);
                        } else {
                            $restorer->fileUpload(1);
                        }

                        $restorer->setUser($user);
                        $restorer->setCategory($input["category"]);
                        $manager->persist($restorer); //commit(git)
                        $manager->flush(); // push(git)
                        $this->addFlash('success', 'Vous êtes inscris');
                        return $this->redirectToRoute('loginRestaurent');
                    }
                }
            }
        }
        return $this->render('restorer/register.html.twig', [
            'restorerForm' => $form->createView(),
            'error' => $error
        ]);
    }

    /**
     * @Route("/restaurent/{id}", name="restorerDish")
     */
    public function restorerDish($id)
    {
        $userLog = $this->getUser();
        $repository = $this->getDoctrine()->getRepository(Member::class);
        $member = $repository->findOneBy([
            "user" => $userLog
        ]);
        if ($userLog === null) {
            $member = "";
        }
        $repository = $this->getDoctrine()->getRepository(Restorer::class);
        $restorer = $repository->findBy([
            "id" => $id
        ]);
        $repository = $this->getDoctrine()->getRepository(Dish::class);
        $repoNote = $this->getDoctrine()->getRepository(Note::class);
        //recupere les plats du restaurents
        $allDishs = $repository->findBy([
            "restorer" => $id
        ]);
        $dishs = [];
        // recupere la note moyenne du restaurent
        foreach ($allDishs as $key => $dish) {
            $dishs[$key][0] = $dish;
            $dishs[$key][1] = $repoNote->dishNote($dish);
        }
        return $this->render('restorer/dish.html.twig', [
            "dishs" => $dishs,
            'restorer' => $restorer[0],
            'member' => $member
        ]);
    }

    /**
     * @Route("/restaurents", name="listRestorer")
     */
    public function listRestorer()
    {
        $userLog = $this->getUser();
        $repository = $this->getDoctrine()->getRepository(Member::class);
        $member = $repository->findOneBy([
            "user" => $userLog
        ]);
        if ($userLog === null) {
            $member = "";
        }
        $repository = $this->getDoctrine()->getRepository(Restorer::class);
        // on recupere tout les resto
        $allRestorers = $repository->findAll();
        $repoNote = $this->getDoctrine()->getRepository(Note::class);
        $restorers = [];
        // on recupere la note moyenne de tous les resto
        foreach ($allRestorers as $key => $restorer) {
            $restorers[$key][0] = $restorer;
            $restorers[$key][1] = $repoNote->dishNoteRestaurent($restorer);
        }
        return $this->render('restorer/listRestorer.html.twig', [
            'restorers' => $restorers,
            'member' => $member
        ]);
    }

    /**
     * @Route("/restaurents/{cat}", name="catListRestorer")
     */
    public function catListRestorer($cat)
    {
        $repository = $this->getDoctrine()->getRepository(Restorer::class);
        // on recupere les restos par categories
        $allRestorers = $repository->listRestorerByCategory($cat);
        $repoNote = $this->getDoctrine()->getRepository(Note::class);
        $restorers = [];
        // on recupere la note moyenne de tous les resto
        foreach ($allRestorers as $key => $restorer) {
            $restorers[$key][0] = $restorer;
            $restorers[$key][1] = $repoNote->dishNoteRestaurent($restorer);
        }
        return $this->render('restorer/listRestorer.html.twig', [
            'restorers' => $restorers
        ]);
    }

    /**
     * @Route("/searchRestorer", name="searchRestorer")
     */
    public function searchRestorer(Request $request): Response
    {
        $name = $request->get('name');
        $repository = $this->getDoctrine()->getRepository(Restorer::class);
        // on recupere tout les resto en like par rapport a la barre de recherche
        $allRestorers = $repository->searchRestorer($name);
        $repoNote = $this->getDoctrine()->getRepository(Note::class);
        $restorers = [];
        // on recupere la note moyenne de tous les resto
        foreach ($allRestorers as $key => $restorer) {
            $restorers[$key][0] = $restorer;
            $restorers[$key][1] = $repoNote->dishNoteRestaurent($restorer);
        }
        // on renvoie dans le html les infos avec le bon style
        foreach ($restorers as $key => $restorer) {
            echo '<div class="container__middle--content"><a href="/restaurent/' . $restorer[0]->getId() . '">';
            if ($restorer[0]->getLogo() != "default.png") {
                echo '<img src="/restorer/' . $restorer[0]->getId() . '/logo/' . $restorer[0]->getLogo() . '" alt="">';
            } else {
                echo '<img src="/img/' . $restorer[0]->getLogo() . '" alt="">';
            }
            echo '</a>';
            echo '<p>' . $restorer[0]->getName() . '</p>
            <p> frais de livraison : 2,5€ - 1h </p>';
            if ($restorer[1][0]["note"] != null) {
                echo '<p>' . $restorer[1][0]["note"] . '</p>';
            } else {
                echo '<p> ce restaurent n\'a encore aucune note </p>';
            };
            echo '</div>';
        }
        return new Response();
    }

    // meme chose que celle d'au dessus juste pas le meme design
    /**
     * @Route("/searchListRestorer", name="searchListRestorer")
     */
    public function searchListRestorer(Request $request): Response
    {
        $name = $request->get('name');
        $repository = $this->getDoctrine()->getRepository(Restorer::class);
        $allRestorers = $repository->searchRestorer($name);
        $repoNote = $this->getDoctrine()->getRepository(Note::class);
        $restorers = [];
        foreach ($allRestorers as $key => $restorer) {
            $restorers[$key][0] = $restorer;
            $restorers[$key][1] = $repoNote->dishNoteRestaurent($restorer);
        }
        echo '<div class="container__body">';
        foreach ($restorers as $key => $restorer) {
            echo '<div class="container__body--middle"><div class="container__body--content">';
            echo '<span>chez ' . $restorer[0]->getName() . '</span>
            <p> frais de livraison : 2,5€ - 1h </p>';
            if ($restorer[1][0]["note"] != null) {
                echo '<p>' . $restorer[1][0]["note"] . '</p>';
            } else {
                echo '<p> aucune note </p>';
            };
            echo '<a href="/restaurent/' . $restorer[0]->getId() . '">';
            echo '</div> <div class="container__body--content-img">';
            if ($restorer[0]->getLogo() != "default.png") {
                echo '<img src="/restorer/' . $restorer[0]->getId() . '/logo/' . $restorer[0]->getLogo() . '" alt="">';
            } else {
                echo '<img src="/img/' . $restorer[0]->getLogo() . '" alt="">';
            }
            echo '</div>';
            echo '</a>';
            echo '</div>';
        }
        echo '</div>';
        return new Response();
    }

    // meme chose mais avec les plats
    /**
     * @Route("/searchDish", name="searchDish")
     */
    public function searchDish(Request $request): Response
    {
        $name = $request->get('name');
        $restorer = $request->get('restorer');
        $repository = $this->getDoctrine()->getRepository(Dish::class);
        $allDishs = $repository->searchDish($name, $restorer);
        $repoNote = $this->getDoctrine()->getRepository(Note::class);
        $dishs = [];
        foreach ($allDishs as $key => $dish) {
            $dishs[$key][0] = $dish;
            $dishs[$key][1] = $repoNote->dishNote($dish);
        }

        foreach ($dishs as $key => $dish) {
            if ($dish[0]->getImg() === "image.png") {
                echo '<img src="/dish/' . $dish[0]->getId() . '/img/' . $dish[0]->getImg() . '" alt="">';
            } else {
                echo '<img src="/dishs/' . $dish[0]->getId() . '/' . $dish[0]->getImg() . '" alt="">';
            }
            echo '<p>' . $dish[0]->getName() . '</p>
             <p> frais de livraison : 2,5€ - 1h </p>';
            if ($dish[1][0]["note"] != null) {
                echo '<p>' . $dish[1][0]["note"] . '</p>';
            } else {
                echo '<p> ce restaurent n\'a encore aucune note </p>';
            }
            echo '<form action="" method="post">
                <button type="submit"><a href="/basket/add/' . $dish[0]->getId() . '/' . $dish[0]->getRestorer()->getId()  . '">Ajouter</a></button>
            </form>';
        }
        return new Response();
    }
}
