<?php

namespace App\Components\Authentication\Communication;

use App\Components\Authentication\Business\AuthenticationBusinessFacade;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

class ApiCheckTokenController extends AbstractController
{
    public function __construct
    (
        private readonly AuthenticationBusinessFacade $authenticationBusinessFacade
    )
    {
    }

    #[Route('/api/check-token', name: 'api_check_token')]
    public function verifyAuthorization(Request $request): Response
    {
        $user = null;
        $accessToken = $request->headers->get('Authorization');

        if ($accessToken !== null) {
            $user = $this->authenticationBusinessFacade->getUserFromToken($accessToken);
        }

        return $this->json([
            'valid' => true,
            'user' => $user
        ]);
    }
}
