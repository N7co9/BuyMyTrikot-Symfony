<?php
declare(strict_types=1);

namespace App\Components\Orderflow\Business\Model;

use App\Components\Authentication\Persistence\ApiTokenRepository;
use App\Components\Orderflow\Communication\Mapping\Request2OrderDTO;
use App\Components\Orderflow\Persistence\BillingAddressRepository;
use App\Components\Orderflow\Persistence\OrdersRepository;
use App\Components\ShoppingCart\Business\ShoppingCartBusinessFacade;
use App\Components\ShoppingCart\Persistence\ShoppingCartRepository;
use App\Entity\BillingAddress;
use App\Entity\Orders;
use App\Global\DTO\OrderDTO;
use App\Global\DTO\UserDTO;
use App\Global\Service\Mapping\UserMapper;
use Symfony\Component\HttpFoundation\Request;

class OrderFlowRead
{
    public function __construct
    (
        private readonly ApiTokenRepository         $apiTokenRepository,
        private readonly UserMapper                 $userMapper,
        private readonly ShoppingCartBusinessFacade $shoppingCartBusinessFacade,
        private readonly BillingAddressRepository   $billingAddressRepository,
        private readonly ShoppingCartRepository     $shoppingCartRepository,
        private readonly Request2OrderDTO           $request2OrderDTO,
        private readonly OrdersRepository           $ordersRepository
    )
    {
    }

    public function fetchShoppingCartInformation(Request $request): ?array
    {
        $userDTO = $this->fetchUserInformationFromAuthentication($request);

        $deliveryMethod = $this->shoppingCartBusinessFacade->fetchDeliveryMethod($request);

        if ($userDTO) {
            $cart = $this->shoppingCartBusinessFacade->getCart($request);
            $expenses = $this->shoppingCartBusinessFacade->calculateExpenses($cart, $deliveryMethod);
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

    public function fetchBillingInformation(Request $request): ?BillingAddress
    {
        return $this->billingAddressRepository->findByUserId($this->fetchUserInformationFromAuthentication($request)->id);
    }

    public function buildOrderDTO(Request $request): mixed
    {
        $userDTO = $this->fetchUserInformationFromAuthentication($request);
        $orderDTO = $this->request2OrderDTO->mapRequestOrderToDto($request);

        $orderDTO->email = $userDTO->email;
        $orderDTO->items = $this->shoppingCartRepository->findCartItemsByUserId($userDTO->id);

        return $orderDTO;
    }

    public function fetchMostRecentOrder(Request $request): Orders
    {
        $userDTO = $this->fetchUserInformationFromAuthentication($request);
        return $this->ordersRepository->findMostRecentOrderByEmail($userDTO->email);
    }
}