<?php

namespace App\Components\ShoppingCart\Communication;

use App\Components\ShoppingCart\Business\ShoppingCartBusinessFacadeInterface;
use App\Components\ShoppingCart\Dto\ShoppingCartSaveDTO;
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
    public function index(): Response
    {
        $userID = $this->getLoggingUser()->id;
        $items = $this->facade->getItems($userID);
        $total = $this->facade->getTotal($userID);
        return $this->render('shopping_cart/index.html.twig', ['controller_name' => 'ShoppingCartController',
            'items' => $items,
            'total' => $total
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
}
