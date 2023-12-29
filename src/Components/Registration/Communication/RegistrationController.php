<?php

namespace App\Components\Registration\Communication;

use App\Components\Registration\Business\RegistrationBusinessFacade;
use App\Components\Registration\Business\RegistrationBusinessFacadeInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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

    #[Route('/register', name: 'app_register', methods: ['POST', 'GET'])]
    public function register(Request $request): Response
    {
        if ($request->getMethod() === 'POST') {

            $userDTO = $this->facade->mapRequestToUserDto($request);

            $errors = $this->facade->validate($userDTO);

            if (empty($errors)) {
                $errors = $this->facade->register($userDTO);
            }
        }

        return $this->render('registration/index.html.twig', [
            'user' => $userDTO ?? null,
            'errors' => $errors ?? []
        ]);
    }
}