<?php
declare(strict_types=1);

namespace App\Components\Payment\Communication;

use App\Components\Payment\Business\Model\PaymentHandling;
use App\Global\Service\Stripe\StripeClient;
use App\Symfony\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Router;
use Symfony\Component\Routing\RouterInterface;

class PaymentController extends AbstractController
{
    public function __construct
    (
        private readonly PaymentHandling $paymentHandling,
        private readonly StripeClient    $stripeClient,
        public RouterInterface $router,

    )
    {
    }

    #[Route('/order/payment/{shippingCost}', name: 'app_order_payment')]
    public function index($shippingCost): Response
    {
        $userDTO = $this->getLoggingUser();

        $stripe = $this->stripeClient;

        $redirectUrl = $this->paymentHandling->createCheckoutSession($userDTO, $stripe, $shippingCost, $this->router);

        return $this->redirect($redirectUrl);
    }
}