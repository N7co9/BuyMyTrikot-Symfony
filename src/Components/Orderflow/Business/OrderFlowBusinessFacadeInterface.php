<?php
declare(strict_types=1);

namespace App\Components\Orderflow\Business;

use App\Entity\BillingAddress;
use Symfony\Component\HttpFoundation\Request;

interface OrderFlowBusinessFacadeInterface
{
    public function fetchShoppingCartInformation(Request $request) : ?array;
    public function fetchBillingInformation(Request $request) : ?BillingAddress;

}