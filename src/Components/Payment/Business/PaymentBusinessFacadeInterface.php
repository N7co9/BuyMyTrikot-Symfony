<?php
declare(strict_types=1);

namespace App\Components\Payment\Business;

use App\Global\DTO\ResponseDTO;
use App\Global\Service\Stripe\StripeClient;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;

interface PaymentBusinessFacadeInterface
{

    public function fetchCheckoutSessionInformation(Request $request): ResponseDTO;

    public function buildOrderSuccessInformationArray(Request $request): ResponseDTO;

    public function persistNewSuccessfulPaymentInformation(Request $request): ResponseDTO;

    public function createCheckoutSession(Request $request, StripeClient $stripeClient, RouterInterface $router): string;
}