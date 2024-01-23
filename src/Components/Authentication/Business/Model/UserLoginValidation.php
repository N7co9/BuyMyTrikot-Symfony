<?php

namespace App\Components\Authentication\Business\Model;

use App\Global\Persistence\DTO\ResponseDTO;
use App\Global\Persistence\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class UserLoginValidation
{
    public function __construct(
        private readonly UserRepository $repository,
    )
    {
    }

    public function authenticate(array $formData, SessionInterface $session): ResponseDTO
    {
        $email = $formData['email'];
        $plainPassword = $formData['password'];
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
        $session->set('user', $email);
        return new ResponseDTO('Login successful!', 'OK');
    }
}
