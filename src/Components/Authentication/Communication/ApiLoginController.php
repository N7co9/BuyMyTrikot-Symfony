<?php

namespace App\Components\Authentication\Communication;

use App\Components\Authentication\Business\AuthenticationBusinessFacade;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

class ApiLoginController extends AbstractController
{

    public function __construct
    (
        private readonly AuthenticationBusinessFacade $authenticationBusinessFacade
    )
    {}

    #[Route('/api/login', name: 'api_login', methods: ['POST'])]
    public function login(#[CurrentUser] $user = null): Response
    {
        $accessToken = $this->authenticationBusinessFacade->generateApiToken($user);

        return $this->json([
            'user' => $user ? $user->getId() : null,
            'token' => $accessToken
        ]);
    }
}
