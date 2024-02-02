<?php

namespace App\Components\Orderflow\Communication;

use App\Components\Orderflow\Business\OrderFlowBusinessFacade;
use App\Components\ShoppingCart\Business\ShoppingCartBusinessFacadeInterface;
use App\Symfony\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;


class OrderFlowController extends AbstractController
{
    public function __construct(
        private readonly OrderFlowBusinessFacade             $orderFlowBusinessFacade,
        private readonly ShoppingCartBusinessFacadeInterface $shoppingCartBusinessFacade,
        public ?array                                        $total = null,
    )
    {
    }

    #[Route('/order/flow', name: 'app_order_flow')]
    public function index(Request $request): Response
    {
        $loginUserDto = $this->getLoggingUser();

        $errors = [];
        if ($request->getMethod() === 'POST') {
            $orderDTO = $this->orderFlowBusinessFacade->mapRequestOrderToDto($request);
            $errors = $this->orderFlowBusinessFacade->validate($orderDTO);
            if (empty($errors)) {
                $this->orderFlowBusinessFacade->mapOrderDTO2Session($orderDTO, $request);
                return $this->redirectToRoute('app_order_payment',
                    ['shippingCost' => $orderDTO->shipping]
                );
            }
        }

        $cartDTOList = $this->shoppingCartBusinessFacade->getCart($loginUserDto->id);
        $total = $this->shoppingCartBusinessFacade->calculateExpenses($cartDTOList);

        return $this->render('order_flow/index.html.twig', [
            'email' => $loginUserDto->email,
            'items' => $cartDTOList,
            'costs' => $total,
            'response' => $errors
        ]);
    }

    #[Route('/order/flow/thankyou', name: 'app_order_flow_thankyou')]
    public function success(Request $request): Response
    {
        $orderDTO = $this->orderFlowBusinessFacade->mapSession2OrderDTO($request);
        $this->orderFlowBusinessFacade->createOrder($orderDTO, $this->getLoggingUser());
        $email = $this->getLoggingUser()->email;

        $mostRecentOrder = $this->orderFlowBusinessFacade->getMostRecentOrder($email);

        if (!$mostRecentOrder) {
            throw $this->createNotFoundException('Order not found.');
        }
        $items = $this->orderFlowBusinessFacade->findItemsByArrayOfIds($mostRecentOrder->getItems());

        return $this->render('thank_you/index.html.twig', [
            'items' => $items,
            'order' => $mostRecentOrder,
        ]);
    }
}