<?php

namespace App\Tests\Components\Authentication\Business;

use App\Components\Authentication\Business\Model\UserLoginValidation;
use App\Components\User\Persistence\UserRepository;
use App\Entity\User;
use App\Global\DTO\ResponseDTO;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class UserLoginValidationTest extends TestCase
{
    private UserLoginValidation $userLoginValidation;
    private UserRepository $userRepository;
    private SessionInterface $session;

    protected function setUp(): void
    {
        $this->userRepository = $this->createMock(UserRepository::class);
        $this->session = $this->createMock(SessionInterface::class);
        $this->userLoginValidation = new UserLoginValidation($this->userRepository);
    }

    public function testAuthenticateWithEmptyEmailAndPassword()
    {
        $formData = [
            'email' => '',
            'password' => '',
        ];

        $response = $this->userLoginValidation->authenticate($formData, $this->session);

        $this->assertInstanceOf(ResponseDTO::class, $response);
        $this->assertEquals('Email or password cannot be empty', $response->getMessage());
        $this->assertEquals('Error', $response->getType());
    }

    public function testAuthenticateWithUserNotFound()
    {
        $formData = [
            'email' => 'nonexistent@example.com',
            'password' => 'password123',
        ];

        // Mock UserRepository to return null when findOneByEmail is called
        $this->userRepository->method('findOneByEmail')->willReturn(null);

        $response = $this->userLoginValidation->authenticate($formData, $this->session);

        $this->assertInstanceOf(ResponseDTO::class, $response);
        $this->assertEquals('User not found', $response->getMessage());
        $this->assertEquals('Error', $response->getType());
    }

    public function testAuthenticateWithInvalidCredentials()
    {
        $formData = [
            'email' => 'validation@validation.com',
            'password' => 'invalidPassword',
        ];

        // Create a mock User with a hashed password
        $hashedPassword = password_hash('validPassword', PASSWORD_BCRYPT);
        $user = new User();
        $user->setEmail('existing@example.com');
        $user->setPassword($hashedPassword);

        // Mock UserRepository to return the User when findOneByEmail is called
        $this->userRepository->method('findOneByEmail')->willReturn($user);

        $response = $this->userLoginValidation->authenticate($formData, $this->session);

        $this->assertInstanceOf(ResponseDTO::class, $response);
        $this->assertEquals('Invalid credentials', $response->getMessage());
        $this->assertEquals('Error', $response->getType());
    }

    public function testAuthenticateWithValidCredentials()
    {
        $formData = [
            'email' => 'existing@example.com',
            'password' => 'validPassword',
        ];

        // Create a mock User with a hashed password
        $hashedPassword = password_hash('validPassword', PASSWORD_BCRYPT);
        $user = new User();
        $user->setEmail('existing@example.com');
        $user->setPassword($hashedPassword);

        // Mock UserRepository to return the User when findOneByEmail is called
        $this->userRepository->method('findOneByEmail')->willReturn($user);

        // Mock SessionInterface to set the user session
        $this->session->expects($this->once())->method('set')->with('user', 'existing@example.com');

        $response = $this->userLoginValidation->authenticate($formData, $this->session);

        $this->assertInstanceOf(ResponseDTO::class, $response);
        $this->assertEquals('Login successful!', $response->getMessage());
        $this->assertEquals('OK', $response->getType());
    }
}
