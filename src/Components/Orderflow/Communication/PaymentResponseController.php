<?php
declare(strict_types=1);

namespace App\Components\Orderflow\Communication;

use App\Components\Orderflow\Business\OrderFlowBusinessFacadeInterface;
use App\Components\ShoppingCart\Business\ShoppingCartBusinessFacadeInterface;
use App\Symfony\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PaymentResponseController extends AbstractController
{
    public function __construct
    (
        private readonly OrderFlowBusinessFacadeInterface    $orderFlowBusinessFacade,
        private readonly ShoppingCartBusinessFacadeInterface $shoppingCartBusinessFacade
    )
    {
    }

    #[Route('/order/payment/abort', name: 'app_payment_abort')]
    public function abort(Request $request): Response
    {
        try {
            $this->orderFlowBusinessFacade->removeMostRecentOrder($request);
            return $this->json('OK');
        } catch (\Exception $exception) {
            return $this->json($exception);
        }
    }

    #[Route('/order/payment/success', name: 'app_payment_success')]
    public function success(Request $request): Response
    {
        try {
            $this->orderFlowBusinessFacade->setOrderSuccessful($request);
            $cartInformation = $this->shoppingCartBusinessFacade->getCart($request);
            $expensesInformation = $this->shoppingCartBusinessFacade->calculateExpenses($cartInformation);
            $orderInformation = $this->orderFlowBusinessFacade->fetchMostRecentOrder($request);
            $this->shoppingCartBusinessFacade->removeAllAfterOrderSuccess($request);
            return $this->json(
                [
                    'cartInformation' => $cartInformation,
                    'expenses' => $expensesInformation,
                    'orderInformation' => $orderInformation
                ]);
        } catch (\Exception $exception) {
            return $this->json($exception);
        }
    }
}