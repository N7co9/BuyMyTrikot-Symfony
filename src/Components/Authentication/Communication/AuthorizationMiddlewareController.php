<?php

namespace App\Components\Authentication\Communication;

use App\Components\Authentication\Business\AuthenticationBusinessFacadeInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AuthorizationMiddlewareController extends AbstractController
{
    public function __construct
    (
        private readonly AuthenticationBusinessFacadeInterface $authenticationBusinessFacade
    )
    {
    }

    #[Route('/api/check-token', name: 'api_check_token')]
    public function verifyAuthorization(Request $request): Response
    {
        $response = $this->authenticationBusinessFacade->getUserFromToken($request);

        return $this->json
        (
            $response
        );
    }
}
