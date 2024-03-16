<?php

namespace App\Components\Registration\Communication;

use App\Components\Registration\Business\RegistrationBusinessFacadeInterface;
use App\Global\DTO\ResponseDTO;
use App\Symfony\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RegistrationController extends AbstractController
{
    public function __construct(
        private readonly RegistrationBusinessFacadeInterface $facade,
    )
    {
    }

    #[Route('/api/register', name: 'api_register')]
    public function register(Request $request): Response
    {
        $response = $this->facade->register($request);

        return $this->json($response);
    }
}