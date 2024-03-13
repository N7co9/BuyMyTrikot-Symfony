<?php

namespace App\Components\Orderflow\Communication;

use App\Components\Orderflow\Business\OrderFlowBusinessFacadeInterface;
use App\Global\DTO\ResponseDTO;
use App\Symfony\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class BillingController extends AbstractController
{
    public function __construct
    (
        public readonly OrderFlowBusinessFacadeInterface $orderFlowBusinessFacade
    )
    {
    }

    #[Route('/order/billing/information', name: 'app_billing')]
    public function index(Request $request): Response
    {
        try {
            $billingInformation = $this->orderFlowBusinessFacade->fetchBillingInformation($request);
            $cartInformation = $this->orderFlowBusinessFacade->fetchShoppingCartInformation($request);

            return $this->json(
                [
                    'billingInformation' => $billingInformation,
                    'cartInformation' => $cartInformation
                ]
            );
        } catch (\Exception $exception) {
            return $this->json(
                new ResponseDTO($exception, 'Exception')
            );
        }
    }


    #[Route('/order/persist', name: 'app_billing_persist')]
    public function persist(Request $request): Response
    {
        try {
            $errors = $this->orderFlowBusinessFacade->persistOrder($request);
            return $this->json($errors);
        } catch (\Exception $exception) {
            return $this->json($exception);
        }
    }
}