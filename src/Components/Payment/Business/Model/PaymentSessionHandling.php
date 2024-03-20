<?php
declare(strict_types=1);

namespace App\Components\Payment\Business\Model;

use App\Global\Service\Stripe\StripeClient;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;

/*
 * This Class is exclusively designed to handle the Checkout Session Creation Process from Stripe, our Payment Gateway.
 */

class PaymentSessionHandling
{
    public function __construct
    (
        private readonly PaymentRead $paymentRead,
    )
    {
    }

    public function createCheckoutSession(Request $request, StripeClient $stripeClient, RouterInterface $router): string
    {
        $checkoutInformationContent = $this->paymentRead->fetchCheckoutSessionInformation($request)->content;

        $shoppingCartItemDTOList = $checkoutInformationContent['shoppingCartItemDTOList'];
        $shipping = $checkoutInformationContent['shipping'];


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
            'success_url' => 'http://localhost:3000/payment/success',  /* That's far from clean code but probably sufficient for the time being, since it will never go into a live environment */
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