<?php

namespace App\Service\Validation;

use App\Model\DTO\ResponseDTO;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Request;

class UserLoginValidation
{
    public function __construct(
        private UserRepository $repository,
    )
    {
    }

    public function authenticate(Request $request, SessionInterface $session): ResponseDTO
    {
        if ($request->getMethod() !== 'POST') {
            return new ResponseDTO('', '');
        }

        $email = $request->get('email', '');
        $plainPassword = $request->get('password', '');

        if (empty($email) || empty($plainPassword)) {
            return new ResponseDTO('Email or password cannot be empty', 'Error');
        }

        $user = $this->repository->findOneByEmail($email);

        if (!$user) {
            return new ResponseDTO('User not found', 'Error');
        }

        if (!password_verify($plainPassword, $user->getPassword())) {
            return new ResponseDTO('Invalid credentials', 'Error');
        }
        $session->set('user', $request->get('email'));
        return new ResponseDTO('Login successful!', 'OK');
    }

}
