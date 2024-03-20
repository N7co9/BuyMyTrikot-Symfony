<?php
declare(strict_types=1);

namespace App\Components\Payment\Business\Model;

use App\Components\Orderflow\Business\OrderFlowBusinessFacadeInterface;
use App\Components\ShoppingCart\Business\ShoppingCartBusinessFacadeInterface;
use App\Global\DTO\ResponseDTO;
use Exception;
use Symfony\Component\HttpFoundation\Request;

class PaymentRead
{
    public function __construct
    (
        private readonly ShoppingCartBusinessFacadeInterface $shoppingCartBusinessFacade,
        private readonly OrderFlowBusinessFacadeInterface    $orderFlowBusinessFacade,
    )
    {
    }

    public function buildOrderSuccessInformationArray(Request $request): ResponseDTO
    {
        try {
            $cartInformation = $this->shoppingCartBusinessFacade->getCart($request);
            $expensesInformation = $this->shoppingCartBusinessFacade->calculateExpenses($cartInformation);
            $orderInformation = $this->orderFlowBusinessFacade->fetchMostRecentOrder($request);

            return new ResponseDTO([
                'cartInformation' => $cartInformation,
                'expenses' => $expensesInformation,
                'orderInformation' => $orderInformation
            ], true);
        } catch (Exception $exception) {
            return new ResponseDTO($exception, false);
        }
    }

    public function fetchCheckoutSessionInformation(Request $request): ResponseDTO
    {
        try {
            $shoppingCartItemDTOList = $this->shoppingCartBusinessFacade->getCart($request);
            $shipping = $this->shoppingCartBusinessFacade->fetchShippingCost($request);
            $response =
                [
                    'shoppingCartItemDTOList' => $shoppingCartItemDTOList,
                    'shipping' => $shipping
                ];
            return new ResponseDTO($response, true);
        } catch (Exception $exception) {
            return new ResponseDTO($exception, false);
        }
    }
}