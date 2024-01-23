<?php

namespace App\Components\Registration\Communication;

use App\Components\Registration\Business\RegistrationBusinessFacadeInterface;
use App\Components\Registration\Communication\Mapper\Request2UserDTO;
use App\Symfony\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RegistrationController extends AbstractController
{
    public function __construct(
        private readonly RegistrationBusinessFacadeInterface $facade,
        private readonly Request2UserDTO                     $request2UserDTO,
    )
    {
    }

    #[Route('/register', name: 'app_register', methods: ['POST', 'GET'])]
    public function register(Request $request): Response
    {
        if ($request->getMethod() === 'POST') {
            $userDTO = $this->request2UserDTO->request2DTO($request);
            $errors = $this->facade->register($request);
        }
        if (empty($errors))
        {
            return $this->redirectToRoute('app_login');
        }

        return $this->render('registration/index.html.twig', [
            'user' => $userDTO ?? null,
            'errors' => $errors
        ]);
    }
}