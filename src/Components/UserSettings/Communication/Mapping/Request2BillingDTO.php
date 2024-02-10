<?php
declare(strict_types=1);

namespace App\Components\UserSettings\Communication\Mapping;

use App\Global\DTO\BillingDTO;
use Symfony\Component\HttpFoundation\Request;

class Request2BillingDTO
{
    public function mapRequestToBillingDTO(Request $request): BillingDTO
    {
        return new BillingDTO(
            $request->get('first-name'),
            $request->get('last-name'),
            $request->get('address'),
            $request->get('city'),
            $request->get('country'),
            $request->get('region'),
            $request->get('postal-code'),
            $request->get('phone'),
            $request->get('userId' ?? 0)
        );
    }
}