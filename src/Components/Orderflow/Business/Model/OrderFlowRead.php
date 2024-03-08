<?php
declare(strict_types=1);

namespace App\Components\Orderflow\Business\Model;

use App\Components\Authentication\Persistence\ApiTokenRepository;
use App\Components\Orderflow\Persistence\BillingAddressRepository;
use App\Components\ShoppingCart\Business\ShoppingCartBusinessFacade;
use App\Components\ShoppingCart\Persistence\Dto\ShoppingCartExpensesDto;
use App\Components\ShoppingCart\Persistence\ShoppingCartRepository;
use App\Entity\BillingAddress;
use App\Global\DTO\ResponseDTO;
use App\Global\DTO\UserDTO;
use App\Global\Service\Mapping\UserMapper;
use Symfony\Component\HttpFoundation\Request;

class OrderFlowRead
{
    public function __construct
    (
        private readonly ApiTokenRepository     $apiTokenRepository,
        private readonly UserMapper             $userMapper,
        private readonly ShoppingCartBusinessFacade $shoppingCartBusinessFacade,
        private readonly BillingAddressRepository $billingAddressRepository,
    )
    {
    }

    public function fetchShoppingCartInformation(Request $request): ?array
    {
        $userDTO = $this->fetchUserInformationFromAuthentication($request);

        if ($userDTO) {
            $cart = $this->shoppingCartBusinessFacade->getCart($request);
            $expenses = $this->shoppingCartBusinessFacade->calculateExpenses($cart);
        }

        return
            [
                'cart' => $cart ?? null,
                'expenses' => $expenses ?? null
            ];
    }

    private function fetchUserInformationFromAuthentication(Request $request): ?UserDTO
    {
        $user = $this->apiTokenRepository->findUserByToken($request->headers->get('Authorization'));
        if ($user) {
            return $this->userMapper->mapEntityToDto($user);

        }
        return null;
    }

    public function fetchBillingInformation(Request $request) : ?BillingAddress
    {
        return $this->billingAddressRepository->findByUserId($this->fetchUserInformationFromAuthentication($request)->id);
    }
}