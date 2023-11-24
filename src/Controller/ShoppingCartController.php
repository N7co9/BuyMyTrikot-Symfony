<?php

namespace App\Controller;

use App\Model\EntityManager\ShoppingCartEntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ShoppingCartController extends AbstractController
{
    #[Route('/shopping/cart', name: 'app_shopping_cart')]
    public function index(): Response
    {
        return $this->render('shopping_cart/index.html.twig', [
            'controller_name' => 'ShoppingCartController',
        ]);
    }

    #[Route('/shopping/cart/{slug}', name:'app_shopping_cart_manage')]
    public function manage(string $slug, ShoppingCartEntityManager $manager) : Response
    {
        $manager->manage($slug);
        return $this->redirectToRoute('app_shopping_cart');
    }
}
