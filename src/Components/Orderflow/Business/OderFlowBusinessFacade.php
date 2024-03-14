<?php
declare(strict_types=1);

namespace App\Components\Orderflow\Business;

use App\Components\Orderflow\Business\Model\OrderFlowRead;
use App\Components\Orderflow\Business\Model\OrderFlowWrite;
use App\Entity\BillingAddress;
use App\Entity\Orders;
use Symfony\Component\HttpFoundation\Request;

class OderFlowBusinessFacade implements OrderFlowBusinessFacadeInterface
{
    public function __construct
    (
        public readonly OrderFlowRead $orderFlowRead,
        public readonly OrderFlowWrite $flowWrite
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
    public function persistOrder(Request $request): ?array
    {
        return $this->flowWrite->persistOrder($request);
    }

    public function removeMostRecentOrder(Request $request): void
    {
        $this->flowWrite->removeOrder($request);
    }

    public function setOrderSuccessful(Request $request): void
    {
        $this->flowWrite->setOrderSuccessful($request);
    }

    public function fetchMostRecentOrder(Request $request): Orders
    {
        return $this->orderFlowRead->fetchMostRecentOrder($request);
    }
}