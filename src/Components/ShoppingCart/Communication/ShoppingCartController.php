<?php

namespace App\Components\ShoppingCart\Communication;

use App\Components\ShoppingCart\Business\ShoppingCartBusinessFacadeInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ShoppingCartController extends AbstractController
{
    public function __construct(
        public ShoppingCartBusinessFacadeInterface $facade,
        public Security                            $security
    )
    {
    }

    #[Route('/shopping/cart', name: 'app_shopping_cart')]
    public function index(): Response
    {
        $userID = $this->facade->findOneByEmail($this->security);
        $items = $this->facade->getItems($userID);
        $total = $this->facade->getTotal($userID);
        return $this->render('shopping_cart/index.html.twig', ['controller_name' => 'ShoppingCartController',
            'items' => $items,
            'total' => $total
        ]);
    }

    #[Route('/shopping/cart/{slug}', name: 'app_shopping_cart_manage')]
    public function manage(string $slug, Request $request): Response
    {
        $itemId = $request->get('id');
        try {
            $cartObject = $this->facade->manageCart($slug, $itemId);

            if ($cartObject === null) {
                throw new \RuntimeException('Failed to manage cart.');
            }

            $this->facade->persist($cartObject);
        } catch (\Exception $e) {
            return $this->render('exceptions/404.html.twig');
        }
        return $this->redirectToRoute('app_shopping_cart');
    }
}
