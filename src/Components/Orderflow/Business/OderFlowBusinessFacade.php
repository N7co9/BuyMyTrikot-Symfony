<?php
declare(strict_types=1);

namespace App\Components\Orderflow\Business;

use App\Components\Orderflow\Business\Model\OrderFlowRead;
use App\Entity\BillingAddress;
use Symfony\Component\HttpFoundation\Request;

class OderFlowBusinessFacade implements OrderFlowBusinessFacadeInterface
{
    public function __construct
    (
        public readonly OrderFlowRead $orderFlowRead
    )
    {
    }

    public function fetchBillingInformation(Request $request) : ?BillingAddress
    {
        return $this->orderFlowRead->fetchBillingInformation($request);
    }

    public function fetchShoppingCartInformation(Request $request) :?array
    {
        return $this->orderFlowRead->fetchShoppingCartInformation($request);
    }
}