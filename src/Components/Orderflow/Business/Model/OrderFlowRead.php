<?php
declare(strict_types=1);

namespace App\Components\Orderflow\Business\Model;

use App\Components\Orderflow\Communication\Mapping\Request2OrderDTO;
use App\Components\Orderflow\Persistence\BillingAddressRepository;
use App\Components\Orderflow\Persistence\OrdersRepository;
use App\Components\ShoppingCart\Persistence\ShoppingCartRepository;
use App\Components\User\Business\UserBusinessFacadeInterface;
use App\Entity\Orders;
use App\Global\DTO\ResponseDTO;
use Symfony\Component\HttpFoundation\Request;

class OrderFlowRead
{
    public function __construct
    (
        private readonly UserBusinessFacadeInterface $userBusinessFacade,
        private readonly BillingAddressRepository    $billingAddressRepository,
        private readonly ShoppingCartRepository      $shoppingCartRepository,
        private readonly Request2OrderDTO            $request2OrderDTO,
        private readonly OrdersRepository            $ordersRepository
    )
    {
    }


    public function fetchBillingInformation(Request $request): ResponseDTO
    {
        try {
            $user = $this->userBusinessFacade->fetchUserInformationFromAuthentication($request)->content;
            $billingAddress = $this->billingAddressRepository->findByUserId($user->id);
            return new ResponseDTO($billingAddress, true);
        } catch (\Exception $exception) {
            return new ResponseDTO($exception, false);
        }
    }

    public function buildOrderDTO(Request $request): ResponseDTO
    {
        $userDTO = $this->userBusinessFacade->fetchUserInformationFromAuthentication($request)->content;

        try {
            $orderDTO = $this->request2OrderDTO->mapRequestOrderToDto($request);
        } catch (\JsonException $e) {
            return new ResponseDTO($e, false);
        }
        if ($userDTO) {
            $orderDTO->email = $userDTO->email;
            $orderDTO->items = $this->shoppingCartRepository->findCartItemsByUserId($userDTO->id);

            return new ResponseDTO($orderDTO, true);
        }
        return new ResponseDTO('An error occurred while building the OrderDTO', false);
    }

    public function fetchMostRecentOrder(Request $request): Orders
    {
        $userDTO = $this->userBusinessFacade->fetchUserInformationFromAuthentication($request)->content;
        return $this->ordersRepository->findMostRecentOrderByEmail($userDTO->email);
    }
}