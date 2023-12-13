<?php

namespace App\Tests\Service\Validation;

use App\Entity\User;
use App\Model\DTO\UserDTO;
use App\Service\Validation\UserLoginValidation;
use App\Model\DTO\ResponseDTO;
use App\Model\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use PHPUnit\Framework\TestCase;

class UserLoginValidationTest extends TestCase
{
    private UserLoginValidation $userLoginValidation;
    private UserRepository $userRepository;
    private SessionInterface $session;

    protected function setUp(): void
    {
        // Create a mock UserRepository
        $this->userRepository = $this->createMock(UserRepository::class);

        // Create a mock SessionInterface
        $this->session = $this->createMock(SessionInterface::class);

        // Instantiate the UserLoginValidation class with mock dependencies
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
