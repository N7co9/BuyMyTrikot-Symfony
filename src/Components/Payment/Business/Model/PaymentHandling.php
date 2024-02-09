<?php
declare(strict_types=1);

namespace App\Components\Payment\Business\Model;

use App\Components\ShoppingCart\Business\ShoppingCartBusinessFacadeInterface;
use App\Global\DTO\UserDTO;
use App\Global\Service\Stripe\StripeClient;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;

class PaymentHandling
{
    public function __construct
    (
        private readonly ShoppingCartBusinessFacadeInterface $shoppingCartBusinessFacade,
    )
    {
    }

    public function createCheckoutSession(UserDTO $userDTO, StripeClient $stripeClient, string $shipping, RouterInterface $router): string
    {
        $shoppingCartItemDTOList = $this->shoppingCartBusinessFacade->getCart($userDTO->id);

        $stripe = $stripeClient->getStripeClient();

        $line_items = [];

        foreach ($shoppingCartItemDTOList as $item) {
            $line_items[] = [
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => $item->itemId,
                    ],
                    'unit_amount' => $this->convertPrices($item->price),
                ],
                'quantity' => $item->quantity,
            ];
        }

        $successUrl = $router->generate('app_order_flow_success', [], UrlGeneratorInterface::ABSOLUTE_URL);
        $abortUrl = $router->generate('app_order_flow_abort', [], UrlGeneratorInterface::ABSOLUTE_URL);

        $line_items[] = [
            'price_data' => [
                'currency' => 'usd',
                'product_data' => [
                    'name' => 'Shipping Cost',
                ],
                'unit_amount' => $shipping,
                'tax_behavior' => 'inclusive',
            ],
            'quantity' => 1,
        ];

        $query = [
            'line_items' => $line_items,
            'mode' => 'payment',
            'success_url' => $successUrl,
            'cancel_url' => $abortUrl,
            'automatic_tax' => ['enabled' => true],
            'customer_email' => $userDTO->email,
        ];

        $checkout_session = $stripe->checkout->sessions->create($query);
        return $checkout_session->url;
    }

    private function convertPrices($floatValue)
    {
        return (int)round($floatValue * 100);
    }

}