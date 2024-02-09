<?php

namespace App\Components\Orderflow\Communication;

use App\Components\Orderflow\Business\OrderFlowBusinessFacade;
use App\Components\ShoppingCart\Business\ShoppingCartBusinessFacadeInterface;
use App\Symfony\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class BillingController extends AbstractController
{
    public function __construct(
        private readonly OrderFlowBusinessFacade             $orderFlowBusinessFacade,
        private readonly ShoppingCartBusinessFacadeInterface $shoppingCartBusinessFacade,
        public ?array                                        $total = null,
    )
    {
    }

    #[Route('/order/billing/', name: 'app_billing')]
    public function index(Request $request): Response
    {
        $loginUserDto = $this->getLoggingUser();
        $errors = [];
        if ($request->getMethod() === 'POST') {
            $orderDTO = $this->orderFlowBusinessFacade->mapRequestOrderToDto($request);
            $errors = $this->orderFlowBusinessFacade->validate($orderDTO);
            if (empty($errors)) {
                $this->orderFlowBusinessFacade->addOrderToSession($orderDTO, $request);
                return $this->redirectToRoute('app_order_payment',
                    ['shippingCost' => $orderDTO->shipping]
                );
            }
        }

        $cartDTOList = $this->shoppingCartBusinessFacade->getCart($loginUserDto->id);
        $total = $this->shoppingCartBusinessFacade->calculateExpenses($cartDTOList);

        return $this->render('order_billing/index.html.twig', [
            'email' => $loginUserDto->email,
            'items' => $cartDTOList,
            'costs' => $total,
            'response' => $errors
        ]);
    }
}