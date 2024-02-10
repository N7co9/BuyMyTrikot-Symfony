<?php
declare(strict_types=1);

namespace App\Components\UserSettings\Communication\Mapping;

use App\Entity\BillingAddress;
use App\Global\DTO\BillingDTO;

class BillingEntity2BillingDTO
{
    public function map(BillingAddress $billingAddress): BillingDTO
    {
        return new BillingDTO(
            $billingAddress->getFirstName(),
            $billingAddress->getLastName(),
            $billingAddress->getAddress(),
            $billingAddress->getCity(),
            $billingAddress->getCountry(),
            $billingAddress->getRegion(),
            $billingAddress->getZipCode(),
            $billingAddress->getPhoneNumber(),
            $billingAddress->getUserId()
        );
    }
}
