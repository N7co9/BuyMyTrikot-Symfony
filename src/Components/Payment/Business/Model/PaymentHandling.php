<?php
declare(strict_types=1);

namespace App\Components\Payment\Business\Model;

use App\Components\ShoppingCart\Business\ShoppingCartBusinessFacadeInterface;
use App\Global\Service\Stripe\StripeClient;
use Symfony\Component\HttpFoundation\Request;
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

    public function createCheckoutSession(Request $request, StripeClient $stripeClient, RouterInterface $router): string
    {
        $shoppingCartItemDTOList = $this->shoppingCartBusinessFacade->getCart($request);
        $shipping = $this->shoppingCartBusinessFacade->fetchShippingCost($request);


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


        $line_items[] = [
            'price_data' => [
                'currency' => 'usd',
                'product_data' => [
                    'name' => 'Shipping Cost',
                ],
                'unit_amount' => $this->convertPrices($shipping),
                'tax_behavior' => 'inclusive',
            ],
            'quantity' => 1,
        ];

        $query = [
            'line_items' => $line_items,
            'mode' => 'payment',
            'success_url' => 'http://localhost:3000/payment/success',
            'cancel_url' => 'http://localhost:3000/payment/abort',
            'automatic_tax' => ['enabled' => true],
        ];

        $checkout_session = $stripe->checkout->sessions->create($query);
        return $checkout_session->url;
    }

    private function convertPrices($floatValue)
    {
        return (int)round($floatValue * 100);
    }

}