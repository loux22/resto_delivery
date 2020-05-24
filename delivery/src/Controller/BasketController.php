<?php

namespace App\Controller;

use App\Entity\Command;
use App\Entity\CommandDish;
use App\Entity\Member;
use App\Entity\Restorer;
use App\Repository\DishRepository;
use App\Repository\RestorerRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class BasketController extends AbstractController
{
    /**
     * @Route("/basket", name="basket")
     */
    public function index(SessionInterface $session, DishRepository $dishRepository, Request $request)
    {
        $userLog = $this->getUser();
        if ($userLog == null) {
            $this->addFlash('errors', 'il faut être connecté pour accéder au panier');
            return $this->redirectToRoute('login');
        }
        $memberRepository = $this->getDoctrine()->getRepository(Member::class);
        $member = $memberRepository->findOneBy([
            'user' => $userLog
        ]);
        $basket = $session->get('basket', []);
        $basketData = [];
        $restorer = "";
        foreach ($basket as $id => $quantity) {
            $basketData[] = [
                'dish' => $dishRepository->find($id),
                'quantity' => $quantity
            ];
        }
        $total = 0;
        foreach ($basketData as $key => $value) {
            $totalDish = $value['dish']->getPrice() * $value['quantity'];
            $total += $totalDish;
        }
        if (!empty($basketData)) {
            $RestorerRepository = $this->getDoctrine()->getRepository(Restorer::class);
            $restorer = $RestorerRepository->find($basketData[0]['dish']->getRestorer()->getId());
            $restorer->getName();
        }

        $buttonCommand = $request->request->all();
        if (isset($buttonCommand['command'])) {
            if ($total > $member->getSold()) {
                $this->addFlash('errors', 'Tu n\'a pas assez d\'argent pour passer cette commande');
            } else {
                $manager = $this->getDoctrine()->getManager();
                $member -> setSold(($member -> getSold()) - ($total));
                $manager->persist($member); //commit(git)
                $manager->flush(); // push(git)
                $command = new Command;
                $command->setUser($userLog);
                $command->setDelivery(new \DateTime("+ 3 hours"));
                $command->setPrice($total + 2.5);
                $command->setStatus(false); 
                $manager->persist($command); //commit(git)
                $manager->flush(); // push(git)
                foreach ($basketData as $key => $basket) {
                    $commandDish = new CommandDish;
                    $commandDish->setCommand($command);
                    $commandDish->setDish($basket['dish']);
                    $commandDish->setQuantity($basket['quantity']);
                    $manager->persist($commandDish); //commit(git)
                    $manager->flush();
                }
                $session->clear();
                $this->addFlash('success', 'Votre commande a bien été passé');
                return $this->redirectToRoute("historicCommand");
            }
        }

        return $this->render('basket/basket.html.twig', [
            'basket' => $basketData,
            'total' => $total,
            'restorer' => $restorer
        ]);
    }

    /**
     * @Route("/basket/add/{id}/{restorer}", name="basket_add")
     */
    public function add($id, $restorer,  SessionInterface $session, DishRepository $dishRepository)
    {
        $userLog = $this->getUser();
        if ($userLog == null) {
            $this->addFlash('errors', 'il faut être connecté pour commander');
            return $this->redirectToRoute('login');
        }
        // si je n'ai pas de panier je veux un tableau vide 
        $basket = $session->get('basket', []);
        $status = false;
        $dishBasket = $id;
        $dishAct = $dishRepository->find($id);
        if (!empty($basket[$id])) {
            $basket[$id]++;
            $this->addFlash('success', 'le plat ' . $dishAct->getName() . ' a été commander une fois de plus');
        } else {
            foreach ($basket as $id => $quantity) {
                $dish = $dishRepository->find($id);
                if ($dish->getRestorer()->getId() != $restorer) {
                    $status = true;
                }
            }
            if ($status === false) {
                $basket[$dishBasket] = 1;
                $this->addFlash('success', 'ton plat a bien été ajouter au panier');
            } else {
                $this->addFlash('errors', 'tu ne peux pas commandé un plat d\'un autre restaurent');
            }
        }
        $session->set('basket', $basket);
        return $this->redirectToRoute("restorerDish", ['id' => $restorer]);
    }

    /**
     * @Route("/basket/remove/{id}", name="basket_remove")
     */
    public function remove($id, SessionInterface $session, DishRepository $dishRepository)
    {
        // si je n'ai pas de panier je veux un tableau vide 
        $basket = $session->get('basket', []);
        $dishAct = $dishRepository->find($id);
        if (!empty($basket[$id])) {
            if ($basket[$id] == 1) {
                $this->addFlash('success', 'ton plat ' . $dishAct->getName() . ' a bien été supprimer');
                unset($basket[$id]);
            } else {
                $basket[$id]--;
            }
        }

        $session->set('basket', $basket);
        return $this->redirectToRoute("basket");
    }
}
