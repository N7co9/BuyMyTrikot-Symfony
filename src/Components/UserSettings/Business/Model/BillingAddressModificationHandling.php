<?php
declare(strict_types=1);

namespace App\Components\UserSettings\Business\Model;

use App\Components\Authentication\Persistence\ApiTokenRepository;
use App\Components\Orderflow\Persistence\BillingAddressRepository;
use App\Components\UserSettings\Communication\Form\BillingAddressValidation;
use App\Components\UserSettings\Communication\Mapping\BillingDTO2BillingEntity;
use App\Components\UserSettings\Communication\Mapping\BillingEntity2BillingDTO;
use App\Components\UserSettings\Communication\Mapping\Request2BillingDTO;
use App\Entity\BillingAddress;
use App\Global\DTO\BillingDTO;
use App\Global\DTO\UserDTO;
use App\Global\Service\Mapping\UserMapper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class BillingAddressModificationHandling
{
    public function __construct
    (
        private readonly EntityManagerInterface   $entityManager,
        private readonly BillingAddressValidation $billingAddressValidation,
        private readonly Request2BillingDTO       $request2BillingDTO,
        private readonly BillingDTO2BillingEntity $billingDTO2BillingEntity,
        private readonly BillingAddressRepository $billingAddressRepository,
        private readonly BillingEntity2BillingDTO $billingEntity2BillingDTO,
        private readonly UserMapper               $userMapper,
        private readonly ApiTokenRepository       $apiTokenRepository,
    )
    {
    }

    private function validateNewBillingAddress(Request $request, UserDTO $userDTO): array
    {
        $BillingDTO = $this->getBillingDTOFromRequest($request, $userDTO);
        return $this->billingAddressValidation->validate($BillingDTO);
    }

    public function setNewBillingAddress(Request $request): array
    {
        $userDTO = $this->userMapper->mapEntityToDto($this->apiTokenRepository->findUserByToken($request->headers->get('Authorization')));

        $res = $this->validateNewBillingAddress($request, $userDTO);
        $BillingDTO = $this->getBillingDTOFromRequest($request, $userDTO);

        $currentBilling = $this->billingAddressRepository->findByUserId($userDTO->id);
        if (empty($res)) {
            if ($currentBilling instanceof BillingAddress) {
                $newBilling = $this->updateOldBilling($currentBilling, $BillingDTO);
                $this->entityManager->persist($newBilling);
                $this->entityManager->flush();
                return [];
            }
            $billing = $this->billingDTO2BillingEntity->map($BillingDTO);
            $billing->setUserId($userDTO->id);
            $this->entityManager->persist($billing);
            $this->entityManager->flush();
            return [];
        }
        return $res;
    }

    private function getBillingDTOFromRequest(Request $request, UserDTO $userDTO): BillingDTO
    {
        return $this->request2BillingDTO->mapRequestToBillingDTO($request, $userDTO);
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

    public function retrieveBillingAddress(Request $request): ?BillingDTO
    {
        $userDTO = $this->userMapper->mapEntityToDto($this->apiTokenRepository->findUserByToken($request->headers->get('Authorization')));
        $billing = $this->billingAddressRepository->findByUserId($userDTO->id);
        if ($billing) {
            return $this->billingEntity2BillingDTO->map($billing);
        }
        return null;
    }
}