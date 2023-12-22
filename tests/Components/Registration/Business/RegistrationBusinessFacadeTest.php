<?php

namespace App\Tests\Components\Registration\Business;

use App\Components\Registration\Business\RegistrationBusinessFacade;
use App\Components\Registration\Business\Validation\UserRegistrationValidation;
use App\Components\Registration\Persistence\UserEntityManager;
use App\Global\Persistence\DTO\ResponseDTO;
use App\Global\Persistence\DTO\UserDTO;
use App\Global\Persistence\Mapping\Mapper;
use Cassandra\Map;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;

class RegistrationBusinessFacadeTest extends TestCase
{
    private RegistrationBusinessFacade $registrationBusinessFacade;
    private UserRegistrationValidation $registrationValidation;
    private Mapper $mapper;
    private UserEntityManager $entityManager;

    protected function setUp(): void
    {
        $this->mapper = $this->createMock(Mapper::class);
        $this->entityManager = $this->createMock(UserEntityManager::class);
        $this->registrationValidation = $this->createMock(UserRegistrationValidation::class);
        $this->registrationBusinessFacade = new RegistrationBusinessFacade($this->registrationValidation, $this->mapper, $this->entityManager);
    }

    public function testValidateUserWithValidData(): void
    {
        // Create a UserDTO with valid data for testing
        $userDTO = new UserDTO();
        $userDTO->username = 'John Doe';
        $userDTO->email = 'john@example.com';
        $userDTO->password = 'Password123!';

        // Mock the validation method to return an empty array (no errors)
        $this->registrationValidation->expects(self::once())->method('validate')->willReturn([]);

        // Call the validate method of the RegistrationBusinessFacade
        $errors = $this->registrationBusinessFacade->validate($userDTO);

        // Assert that there are no errors (empty array)
        self::assertEmpty($errors);
    }

    public function testValidateUserWithInvalidData(): void
    {
        // Create a UserDTO with invalid data for testing
        $userDTO = new UserDTO();
        $userDTO->username = '12345'; // Invalid username
        $userDTO->email = 'invalid-email'; // Invalid email
        $userDTO->password = 'password'; // Invalid password

        // Mock the validation method to return error responses
        $this->registrationValidation->expects(self::once())->method('validate')->willReturn([
            new ResponseDTO('Invalid username', 'Exception'),
            new ResponseDTO('Invalid email', 'Exception'),
            new ResponseDTO('Invalid password', 'Exception'),
        ]);

        // Call the validate method of the RegistrationBusinessFacade
        $errors = $this->registrationBusinessFacade->validate($userDTO);

        // Assert that there are error responses returned
        self::assertCount(3, $errors);
    }
}
