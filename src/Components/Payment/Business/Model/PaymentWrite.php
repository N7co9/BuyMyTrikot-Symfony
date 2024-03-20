<?php
declare(strict_types=1);

namespace App\Components\Payment\Business\Model;

use App\Components\Orderflow\Business\OrderFlowBusinessFacadeInterface;
use App\Components\ShoppingCart\Business\ShoppingCartBusinessFacadeInterface;
use App\Global\DTO\ResponseDTO;
use Exception;
use Symfony\Component\HttpFoundation\Request;

class PaymentWrite
{
    public function __construct
    (
        private readonly OrderFlowBusinessFacadeInterface    $orderFlowBusinessFacade,
        private readonly ShoppingCartBusinessFacadeInterface $shoppingCartBusinessFacade
    )
    {
    }

    public function persistNewSuccessfulPaymentInformation(Request $request): ResponseDTO
    {
        try {
            $this->orderFlowBusinessFacade->setOrderSuccessful($request);
            $this->shoppingCartBusinessFacade->removeAllAfterOrderSuccess($request);
            return new ResponseDTO('', true);
        } catch (Exception $exception) {
            return new ResponseDTO($exception, false);
        }
    }
}