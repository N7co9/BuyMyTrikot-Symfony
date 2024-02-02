<?php
declare(strict_types=1);

namespace App\Global\Service\Stripe;

use Stripe\StripeClient as BaseStripeClient;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class StripeClient
{
    private BaseStripeClient $stripe;

    public function __construct(
        private readonly ParameterBagInterface $parameterBag
    )
    {
        $this->stripe = new BaseStripeClient($this->parameterBag->get('stripe_secret_key'));
    }

    public function getStripeClient(): BaseStripeClient
    {
        return $this->stripe;
    }
}
