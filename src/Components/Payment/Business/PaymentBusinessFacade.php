<?php
declare(strict_types=1);

namespace App\Components\Payment\Business;

use App\Components\Payment\Business\Model\PaymentRead;
use App\Components\Payment\Business\Model\PaymentSessionHandling;
use App\Components\Payment\Business\Model\PaymentWrite;
use App\Global\DTO\ResponseDTO;
use App\Global\Service\Stripe\StripeClient;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;

class PaymentBusinessFacade implements PaymentBusinessFacadeInterface
{
    public function __construct
    (
        private readonly PaymentRead            $paymentRead,
        private readonly PaymentWrite           $paymentWrite,
        private readonly PaymentSessionHandling $paymentHandling
    )
    {
    }

    public function buildOrderSuccessInformationArray(Request $request): ResponseDTO
    {
        return $this->paymentRead->buildOrderSuccessInformationArray($request);
    }

    public function fetchCheckoutSessionInformation(Request $request): ResponseDTO
    {
        return $this->paymentRead->fetchCheckoutSessionInformation($request);
    }

    public function persistNewSuccessfulPaymentInformation(Request $request): ResponseDTO
    {
        return $this->paymentWrite->persistNewSuccessfulPaymentInformation($request);
    }

    public function createCheckoutSession(Request $request, StripeClient $stripeClient, RouterInterface $router): string
    {
        return $this->paymentHandling->createCheckoutSession($request, $stripeClient, $router);
    }

}