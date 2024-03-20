<?php
declare(strict_types=1);

namespace App\Components\UserSettings\Business\Model\BillingAddressModification;

use App\Components\Orderflow\Persistence\BillingAddressRepository;
use App\Components\UserSettings\Communication\Form\BillingAddressInputValidation;
use App\Components\UserSettings\Communication\Mapping\BillingEntity2BillingDTO;
use App\Components\UserSettings\Communication\Mapping\Request2BillingDTO;
use App\Components\User\Business\UserBusinessFacadeInterface;
use App\Global\DTO\BillingDTO;
use App\Global\DTO\UserDTO;
use Symfony\Component\HttpFoundation\Request;

class BillingAddressModificationRead
{
    public function __construct
    (
        private readonly UserBusinessFacadeInterface   $userBusinessFacade,
        private readonly BillingAddressInputValidation $billingAddressInputValidation,
        private readonly Request2BillingDTO            $request2BillingDTO,
        private readonly BillingAddressRepository      $billingAddressRepository,
        private readonly BillingEntity2BillingDTO      $billingEntity2BillingDTO
    )
    {
    }

    public function validateNewBillingAddress(Request $request, UserDTO $userDTO): array
    {
        $BillingDTO = $this->getBillingDTOFromRequest($request, $userDTO);
        return $this->billingAddressInputValidation->validate($BillingDTO);
    }

    public function getBillingDTOFromRequest(Request $request, UserDTO $userDTO): BillingDTO
    {
        return $this->request2BillingDTO->mapRequestToBillingDTO($request, $userDTO);
    }

    public function retrieveBillingAddress(Request $request): ?BillingDTO
    {
        $userDTO = $this->userBusinessFacade->fetchUserInformationFromAuthentication($request)->content;
        $billing = $this->billingAddressRepository->findByUserId($userDTO->id);
        if ($billing) {
            return $this->billingEntity2BillingDTO->map($billing);
        }
        return null;
    }
}