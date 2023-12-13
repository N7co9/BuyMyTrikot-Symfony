<?php

namespace App\Components\Registration\Communication;

use App\Components\Registration\Business\Validation\UserRegistrationValidation;
use App\Components\Registration\Persistence\UserEntityManager;
use App\Global\Persistence\DTO\UserDTO;
use App\Global\Persistence\Mapping\Mapper;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RegistrationController extends AbstractController
{
    public function __construct(
        private UserEntityManager $entityManager,
        private Mapper $mapper,
        private UserRegistrationValidation $userRegistrationValidation
    )
    {}
    #[Route('/register', name: 'app_register', methods: ['POST', 'GET'])]
    public function register(Request $request): Response
    {
        if ($request->getMethod() === 'POST' ) {
            $userDTO = $this->mapper->mapRequest2DTO($request);

            $errors = $this->userRegistrationValidation->validate($userDTO);

            if (empty($errors)) {
                $this->entityManager->register($userDTO);
            }
        }

        return $this->render('registration/index.html.twig', [
            'user' => $userDTO ?? new UserDTO(),
            'errors' => $errors ?? []
        ]);
    }
}