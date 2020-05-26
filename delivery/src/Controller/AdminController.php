<?php

namespace App\Controller;

use App\Entity\Command;
use App\Entity\Restorer;
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
}
