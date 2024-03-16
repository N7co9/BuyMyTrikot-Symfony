<?php
declare(strict_types=1);

namespace App\Components\Settings\Communication\Mapping;

use App\Global\DTO\BillingDTO;
use App\Global\DTO\UserDTO;
use Symfony\Component\HttpFoundation\Request;

class Request2BillingDTO
{
    public function mapRequestToBillingDTO(Request $request, UserDTO $userDTO): BillingDTO
    {
        $data = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);
        return new BillingDTO(
            $data['firstName'],
            $data['lastName'],
            $data['address'],
            $data['city'],
            $data['country'],
            $data['region'],
            $data['postalCode'],
            $data['phoneNumber'],
            $userDTO->id
        );
    }
}