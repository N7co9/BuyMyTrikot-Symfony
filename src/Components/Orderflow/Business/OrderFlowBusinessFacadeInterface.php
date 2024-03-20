<?php
declare(strict_types=1);

namespace App\Components\Orderflow\Business;

use App\Entity\BillingAddress;
use App\Entity\Orders;
use App\Global\DTO\ResponseDTO;
use Symfony\Component\HttpFoundation\Request;

interface OrderFlowBusinessFacadeInterface
{
    public function fetchBillingInformation(Request $request): ResponseDTO;

    public function persistOrder(Request $request): ResponseDTO;

    public function removeMostRecentOrder(Request $request): void;

    public function setOrderSuccessful(Request $request): void;

    public function fetchMostRecentOrder(Request $request): Orders;

}