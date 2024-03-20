<?php
declare(strict_types=1);

namespace App\Components\UserSettings\Business\Model\BillingAddressModification;

use App\Components\Orderflow\Persistence\BillingAddressRepository;
use App\Components\UserSettings\Communication\Mapping\BillingDTO2BillingEntity;
use App\Components\UserSettings\Persistence\BillingAddressEntityManager;
use App\Components\User\Business\UserBusinessFacadeInterface;
use App\Entity\BillingAddress;
use App\Global\DTO\BillingDTO;
use App\Global\DTO\ResponseDTO;
use Symfony\Component\HttpFoundation\Request;

class BillingAddressModificationWrite
{
    public function __construct
    (
        private readonly BillingAddressEntityManager    $billingAddressEntityManager,
        private readonly BillingDTO2BillingEntity       $billingDTO2BillingEntity,
        private readonly BillingAddressRepository       $billingAddressRepository,
        private readonly UserBusinessFacadeInterface    $userBusinessFacade,
        private readonly BillingAddressModificationRead $addressModificationRead,
    )
    {
    }


    public function setNewBillingAddress(Request $request): ResponseDTO
    {
        $userDTO = $this->userBusinessFacade->fetchUserInformationFromAuthentication($request)->content;
        $res = $this->addressModificationRead->validateNewBillingAddress($request, $userDTO);
        $BillingDTO = $this->addressModificationRead->getBillingDTOFromRequest($request, $userDTO);

        $currentBilling = $this->billingAddressRepository->findByUserId($userDTO->id);
        if (empty($res)) {
            if ($currentBilling instanceof BillingAddress) {
                $newBilling = $this->updateOldBilling($currentBilling, $BillingDTO);
                try {
                    $this->billingAddressEntityManager->persist($newBilling);
                    return new ResponseDTO('A Billing Address was updated', true);
                } catch (\Exception $exception) {
                    return new ResponseDTO($exception, false);
                }
            }
            $billing = $this->billingDTO2BillingEntity->map($BillingDTO);
            $billing->setUserId($userDTO->id);
            try {
                $this->billingAddressEntityManager->persist($billing);
                return new ResponseDTO('A new Billing Address was saved', true);
            } catch (\Exception $exception) {
                return new ResponseDTO($exception, false);
            }
        }
        return new ResponseDTO($res, false);
    }


    private function updateOldBilling(BillingAddress $billingAddress, BillingDTO $billingDTO): BillingAddress
    {
        $billingAddress->setFirstName($billingDTO->firstName);
        $billingAddress->setLastName($billingDTO->lastName);
        $billingAddress->setAddress($billingDTO->address);
        $billingAddress->setCity($billingDTO->city);
        $billingAddress->setCountry($billingDTO->country);
        $billingAddress->setRegion($billingDTO->region);
        $billingAddress->setPhoneNumber($billingDTO->phone);
        $billingAddress->setZipCode($billingDTO->postalCode);

        return $billingAddress;
    }

}