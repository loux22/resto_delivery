<?php

namespace App\Controller;

use App\Entity\Dish;
use App\Entity\Note;
use App\Entity\User;
use App\Entity\Restorer;
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
        $repository = $this->getDoctrine()->getRepository(Restorer::class);
        $allRestorers = $repository->findAll();
        $repoNote = $this->getDoctrine()->getRepository(Note::class);
        $restorers = [];
        foreach ($allRestorers as $key => $restorer) {
            $restorers[$key][0] = $restorer;
            $restorers[$key][1] = $repoNote->dishNoteRestaurent($restorer);
        }
        $rand = rand(1, (count($restorers)) - 6);
        return $this->render('restorer/index.html.twig', [
            "restorers" => $restorers,
            "rand" => $rand,
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
                            $restorer->fileUpload($lastRestorer->getId() + 1);
                        } else {
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

    /**
     * @Route("/restaurent/{id}", name="restorerDish")
     */
    public function restorerDish($id)
    {
        $repository = $this->getDoctrine()->getRepository(Restorer::class);
        $restorer = $repository->findBy([
            "id" => $id
        ]);
        $repository = $this->getDoctrine()->getRepository(Dish::class);
        $repoNote = $this->getDoctrine()->getRepository(Note::class);
        $allDishs = $repository->findBy([
            "restorer" => $id
        ]);
        $dishs = [];
        foreach ($allDishs as $key => $dish) {
            $dishs[$key][0] = $dish;
            $dishs[$key][1] = $repoNote->dishNote($dish);
        }
        return $this->render('restorer/dish.html.twig', [
            "dishs" => $dishs,
            'restorer' => $restorer[0]
        ]);
    }

    /**
     * @Route("/restaurents", name="listRestorer")
     */
    public function listRestorer()
    {
        $repository = $this->getDoctrine()->getRepository(Restorer::class);
        $allRestorers = $repository->findAll();
        $repoNote = $this->getDoctrine()->getRepository(Note::class);
        $restorers = [];
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
        $allRestorers = $repository->searchRestorer($name);
        $repoNote = $this->getDoctrine()->getRepository(Note::class);
        $restorers = [];
        foreach ($allRestorers as $key => $restorer) {
            $restorers[$key][0] = $restorer;
            $restorers[$key][1] = $repoNote->dishNoteRestaurent($restorer);
        }
        foreach ($restorers as $key => $restorer) {
            echo '<a href="/restaurent/' . $restorer[0]->getId() . '">';
            if ($restorer[0]->getLogo() === "image.png") {
                echo '<img src="/restorer/' . $restorer[0]->getId() . '/logo/' . $restorer[0]->getLogo() . '" alt="">';
            } else {
                echo '<img src="/img/' . $restorer[0]->getLogo() . '" alt="">';
            }
            echo '<a/>';
            echo '<p>' . $restorer[0]->getName() . '</p>
            <p> frais de livraison : 2,5€ - 1h </p>';
            if ($restorer[1][0]["note"] != null) {
                echo '<p>' . $restorer[1][0]["note"] . '</p>';
            } else {
                echo '<p> ce restaurent n\'a encore aucune note </p>';
            };
        }
        return new Response();
    }


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
                echo '<img src="/img/' . $dish[0]->getImg() . '" alt="">';
            }
            echo '<p>' . $dish[0]->getName() . '</p>
             <p> frais de livraison : 2,5€ - 1h </p>';
            if ($dish[1][0]["note"] != null) {
                echo '<p>' . $dish[1][0]["note"] . '</p>';
            } else {
                echo '<p> ce restaurent n\'a encore aucune note </p>';
            }
            echo '<form action="" method="post">
                <button type="submit"><a href="/basket/add/' . $dish[0]->getId() . '/' . $dish[0]->getRestorer()->getId()  .'">Ajouter</a></button>
            </form>';
        }
        return new Response();
    }
}
