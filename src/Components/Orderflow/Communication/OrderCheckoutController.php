<?php

namespace App\Components\Orderflow\Communication;

use App\Components\Orderflow\Business\OrderFlowBusinessFacadeInterface;
use App\Components\ShoppingCart\Business\ShoppingCartBusinessFacadeInterface;
use App\Global\DTO\ResponseDTO;
use App\Symfony\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use function PHPUnit\Framework\isEmpty;


class OrderCheckoutController extends AbstractController
{
    public function __construct
    (
        public readonly OrderFlowBusinessFacadeInterface     $orderFlowBusinessFacade,
        private readonly ShoppingCartBusinessFacadeInterface $shoppingCartBusinessFacade,
    )
    {
    }

    #[Route('/order/billing/information', name: 'app_billing')]
    public function index(Request $request): Response
    {
        try {
            $billingInformation = $this->orderFlowBusinessFacade->fetchBillingInformation($request)->content;
            $cartInformation = $this->shoppingCartBusinessFacade->fetchShoppingCartInformation($request);
            $content = ['cartInformation' => $cartInformation, 'billingInformation' => $billingInformation];
            return $this->json(
                new ResponseDTO($content, true)
            );
        } catch (\Exception $exception) {
            return $this->json(
                new ResponseDTO($exception, false)
            );
        }
    }

    #[Route('/order/persist', name: 'app_billing_persist')]
    public function persist(Request $request): Response
    {
        try {
            $response = $this->orderFlowBusinessFacade->persistOrder($request);
            return $this->json($response); /* The Status of the Response is handled within 'persistOrder' itself */
        } catch (\Exception $exception) {
            return $this->json($exception);
        }
    }
}