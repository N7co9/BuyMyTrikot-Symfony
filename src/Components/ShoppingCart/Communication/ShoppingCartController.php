<?php

namespace App\Components\ShoppingCart\Communication;

use App\Components\ShoppingCart\Business\ShoppingCartBusinessFacadeInterface;
use App\Symfony\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ShoppingCartController extends AbstractController
{
    public function __construct(
        public ShoppingCartBusinessFacadeInterface $facade,
    )
    {
    }

    #[Route('/shopping/cart', name: 'app_shopping_cart')]
    public function index(Request $request): Response
    {
        $shoppingCartItemDtoList = $this->facade->getCart($request);
        $items = [];
        $expenses = $this->facade->calculateExpenses($shoppingCartItemDtoList);

        foreach ($shoppingCartItemDtoList as $shoppingCartItemDto) {
            $items[] = $shoppingCartItemDto;
        }

        return $this->json(
            [
                'items' => $items,
                'total' => $expenses
            ]
        );
    }

    #[Route('/shopping/cart/add/', name: 'app_shopping_cart_save')]
    public function save(Request $request): Response
    {
        $this->facade->saveItemToCart($request);

        return $this->json(
            'OK'
        );
    }

    #[Route('/shopping/cart/remove/', name: 'app_shopping_cart_remove')]
    public function remove(Request $request): Response
    {
        $this->facade->remove($request);

        return $this->json(
            'OK'
        );
    }

}
