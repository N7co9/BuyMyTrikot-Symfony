<?php
declare(strict_types=1);

namespace App\Components\Orderflow\Business;

use App\Entity\BillingAddress;
use App\Entity\Orders;
use Symfony\Component\HttpFoundation\Request;

interface OrderFlowBusinessFacadeInterface
{
    public function fetchShoppingCartInformation(Request $request): ?array;

    public function fetchBillingInformation(Request $request): ?BillingAddress;

    public function persistOrder(Request $request): ?array;

    public function removeMostRecentOrder(Request $request): void;

    public function setOrderSuccessful(Request $request): void;

    public function fetchMostRecentOrder(Request $request): Orders;

}