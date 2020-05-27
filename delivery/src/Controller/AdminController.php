<?php

namespace App\Controller;

use App\Entity\Command;
use App\Entity\CommandDish;
use App\Entity\Dish;
use App\Entity\Note;
use App\Entity\Restorer;
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
        $nbRestorer = $repository -> findAll();

        $repoCommand = $this->getDoctrine()->getRepository(Command::class);
        $nbCommand = $repoCommand -> findAll();
        $nbCommandInProgress = $repoCommand -> findBy([
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
            $this->addFlash('errors', 'il faut être connecté en tant qu\'admin\' pour acceder au dashboard');
            return $this->redirectToRoute('home');
        }
        if ($userLog->getRoles()[0] != 'ADMIN') {
            $this->addFlash('errors', 'il faut être connecté en tant qu\'admin\' pour acceder au dashboard');
            return $this->redirectToRoute('home');
        }
        $repository = $this->getDoctrine()->getRepository(Restorer::class);
        $allRestorer = $repository -> findAll();
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
            $this->addFlash('errors', 'il faut être connecté en tant qu\'admin\' pour acceder au dashboard');
            return $this->redirectToRoute('home');
        }
        if ($userLog->getRoles()[0] != 'ADMIN') {
            $this->addFlash('errors', 'il faut être connecté en tant qu\'admin\' pour acceder au dashboard');
            return $this->redirectToRoute('home');
        }
        $repository = $this->getDoctrine()->getRepository(Restorer::class);
        $restorer = $repository -> find($id);
        
        $form = $this->createForm(RestorerRegisterType::class, $restorer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($restorer->getFile()) {
                $restorer->removeFile($restorer -> getId());
                $restorer->fileUpload($restorer -> getId());
            }
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($restorer); //commit(git)
            $manager->flush(); // push(git)
            $this->addFlash('success', 'le plat ' . $restorer -> getName() . ' a bien été modifié');
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
            $this->addFlash('errors', 'il faut être connecté en tant qu\'admin\' pour acceder au dashboard');
            return $this->redirectToRoute('home');
        }
        if ($userLog->getRoles()[0] != 'ADMIN') {
            $this->addFlash('errors', 'il faut être connecté en tant qu\'admin\' pour acceder au dashboard');
            return $this->redirectToRoute('home');
        }
        $manager = $this->getDoctrine()->getManager();
        $repository = $this->getDoctrine()->getRepository(Restorer::class);
        $restorer = $repository -> find($id);
        $restorerName = $restorer -> getName();
        $repoNote = $this->getDoctrine()->getRepository(Note::class);
        $repoCommandDish = $this->getDoctrine()->getRepository(CommandDish::class);
        $repoDish = $this->getDoctrine()->getRepository(Dish::class);
        $dishRestorer = $repoDish -> findBy([
            'restorer' => $restorer
        ]);

        $note = [];
        $command = [];
        foreach ($dishRestorer as $key => $dish) {
            $note[] = $repoNote -> findBy([
                "dish" => $dish
            ]);
            $command[] = $repoCommandDish -> findBy([
                'dish' => $dish
            ]);
        }
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
        $manager-> flush();

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
            $this->addFlash('errors', 'il faut être connecté en tant qu\'admin\' pour acceder au dashboard');
            return $this->redirectToRoute('home');
        }
        if ($userLog->getRoles()[0] != 'ADMIN') {
            $this->addFlash('errors', 'il faut être connecté en tant qu\'admin\' pour acceder au dashboard');
            return $this->redirectToRoute('home');
        }
        $repository = $this->getDoctrine()->getRepository(Restorer::class);
        $restorer = $repository -> find($id);
        $repoCommandDish = $this->getDoctrine()->getRepository(CommandDish::class);
        $commandInProgress = $this->getDoctrine()->getRepository(Command::class);
    }
}
