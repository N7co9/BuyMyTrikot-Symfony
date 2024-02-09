<?php

namespace App\Components\ShoppingCart\Communication;

use App\Components\ShoppingCart\Business\ShoppingCartBusinessFacadeInterface;
use App\Components\ShoppingCart\Persistence\Dto\ShoppingCartSaveDTO;
use App\Symfony\AbstractController;
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
    public function index(): Response
    {
        $userID = $this->getLoggingUser()->id;
        $shoppingCartItemDtoList = $this->facade->getCart($userID);

        $items = [];
        $expenses = $this->facade->calculateExpenses($shoppingCartItemDtoList);

        foreach ($shoppingCartItemDtoList as $shoppingCartItemDto) {
            $items[] = $shoppingCartItemDto;
        }

        return $this->render('shopping_cart/index.html.twig', ['controller_name' => 'ShoppingCartController',
            'items' => $items,
            'total' => $expenses
        ]);
    }


    #[Route('/shopping/cart/add/{itemId}/{quantity?}', name: 'app_shopping_cart_save', requirements: ['quantity' => '\d+'], defaults: ['quantity' => 1])]
    public function save(int $itemId, int $quantity): Response
    {
        $userDto = $this->getLoggingUser();

        $shoppingCartDto = new ShoppingCartSaveDTO(
            quantity: $quantity,
            itemId: $itemId,
            userId: $userDto->id
        );

        $this->facade->saveItemToCart($shoppingCartDto);

        return $this->redirectToRoute('app_shopping_cart');
    }

    #[Route('/shopping/cart/remove/{itemId}', name: 'app_shopping_cart_remove')]
    public function remove(int $itemId): Response
    {
        $this->facade->remove($itemId);

        return $this->redirectToRoute('app_shopping_cart');
    }
}
