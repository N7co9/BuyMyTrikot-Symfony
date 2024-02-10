<?php
declare(strict_types=1);

namespace App\Components\UserSettings\Communication\Mapping;

use App\Entity\BillingAddress;
use App\Global\DTO\BillingDTO;

class BillingDTO2BillingEntity
{
    public function map(BillingDTO $billingDTO, ?BillingAddress $billingAddress = null): BillingAddress
    {
        $billingAddress = $billingAddress ?? new BillingAddress();

        $billingAddress->setFirstName($billingDTO->getFirstName());
        $billingAddress->setLastName($billingDTO->getLastName());
        $billingAddress->setAddress($billingDTO->getAddress());
        $billingAddress->setCity($billingDTO->getCity());
        $billingAddress->setCountry($billingDTO->getCountry());
        $billingAddress->setRegion($billingDTO->getRegion());
        $billingAddress->setZipCode($billingDTO->getPostalCode());
        $billingAddress->setPhoneNumber($billingDTO->getPhone());

        if (!$billingAddress->getId()) {
            $billingAddress->setUserId($billingDTO->getUserId());
        }

        return $billingAddress;
    }
}