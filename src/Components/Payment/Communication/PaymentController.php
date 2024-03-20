<?php
declare(strict_types=1);

namespace App\Components\Payment\Communication;

use App\Components\Payment\Business\PaymentBusinessFacadeInterface;
use App\Global\Service\Stripe\StripeClient;
use App\Symfony\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;

class PaymentController extends AbstractController
{
    public function __construct
    (
        private readonly PaymentBusinessFacadeInterface $paymentBusinessFacade,
        private readonly StripeClient                   $stripeClient,
        private readonly RouterInterface                $router,
    )
    {
    }

    #[Route('/order/payment/', name: 'app_order_payment')]
    public function index(Request $request): Response
    {
        $stripe = $this->stripeClient;

        $redirectUrl = $this->paymentBusinessFacade->createCheckoutSession($request, $stripe, $this->router);

        return $this->json(
            $redirectUrl
        );
    }
}