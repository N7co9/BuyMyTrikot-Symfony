<?php

namespace App\Components\Orderflow\Communication;

use App\Components\Orderflow\Business\OrderFlowBusinessFacadeInterface;
use App\Components\Orderflow\Communication\Form\OrderFlowValidation;
use App\Components\ShoppingCart\Business\ShoppingCartBusinessFacadeInterface;
use App\Symfony\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class OrderFlowController extends AbstractController
{
    public function __construct(
        private readonly OrderFlowBusinessFacadeInterface    $orderFlowBusinessFacade,
        private readonly ShoppingCartBusinessFacadeInterface $shoppingCartBusinessFacade,
        private readonly OrderFlowValidation                 $orderFlowValidation,
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

            $orderDto = $this->orderFlowBusinessFacade->mapRequestOrderToDto($request);
            $errors = $this->orderFlowValidation->validate($orderDto);

            if (empty($errors)) {

                $orderDto->email = $loginUserDto->email;
                $this->orderFlowBusinessFacade->createOrder($orderDto, $loginUserDto);

                return $this->redirectToRoute('app_order_flow_thankyou');
            }
        }

        $itemsInCart = $this->orderFlowBusinessFacade->getItemsInCart($loginUserDto->id);
        $total = $this->shoppingCartBusinessFacade->getTotal($loginUserDto->id);

        return $this->render('order_flow/index.html.twig', [
            'email' => $loginUserDto->email,
            'items' => $itemsInCart,
            'costs' => $total,
            'response' => $errors
        ]);
    }

    #[Route('/order/flow/thankyou', name: 'app_order_flow_thankyou')]
    public function success(): Response
    {
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