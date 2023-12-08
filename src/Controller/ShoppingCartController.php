<?php

namespace App\Controller;

use App\Model\EntityManager\ShoppingCartEntityManager;
use App\Model\Repository\ItemRepository;
use App\Model\Repository\ShoppingCartRepository;
use App\Model\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class ShoppingCartController extends AbstractController
{
    #[Route('/shopping/cart', name: 'app_shopping_cart')]
    public function index(
        UserRepository         $userRepository, SessionInterface $session,
        ShoppingCartRepository $cartRepository): Response
    {
        $userID = $userRepository->findOneByEmail($session->get('user'))->getId();
        $items = $cartRepository->findByUserId($userID);
        $total = $cartRepository->getTotal($userID);
        return $this->render('shopping_cart/index.html.twig', ['controller_name' => 'ShoppingCartController',
            'items' => $items,
            'total' => $total
        ]);
    }

    #[Route('/shopping/cart/{slug}', name: 'app_shopping_cart_manage')]
    public function manage(string                 $slug, ShoppingCartEntityManager $manager, Request $request,
                           UserRepository         $userRepository, SessionInterface $session, EntityManagerInterface $entityManager,
                           ShoppingCartRepository $cartRepository, ItemRepository $itemRepository): Response
    {
        $manager->manage($slug, $request, $userRepository, $session, $entityManager, $cartRepository, $itemRepository);
        return $this->redirectToRoute('app_shopping_cart');
    }
}
