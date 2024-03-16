<?php
declare(strict_types=1);

namespace App\Components\Settings\Communication\Form;

use App\Global\DTO\BillingDTO;
use App\Global\DTO\ResponseDTO;

class BillingAddressInputValidation
{
    public function validate(BillingDTO $order): array
    {
        $errorCheck = [
            'firstName' => $this->validateFirstName($order->firstName),
            'lastName' => $this->validateLastName($order->lastName),
            'city' => $this->validateCity($order->city),
            'zip' => $this->validateZip($order->postalCode),
            'address' => $this->validateAddress($order->address),
            'region' => $this->validateState($order->region),
            'phoneNumber' => $this->validatePhoneNumber($order->phone),
        ];

        return array_filter($errorCheck);
    }

    private function validateFirstName($firstName): ?ResponseDTO
    {
        if (empty($firstName) || !is_string($firstName)) {
            return new ResponseDTO('First name cannot be empty and must be a string.', 'Error');
        }

        $firstName = $this->trim($firstName);
        if (strlen($firstName) >= 30 || strlen($firstName) <= 2 ||
            !preg_match('/^[\p{L}\s-]+$/u', $firstName)) {
            return new ResponseDTO('Oops, your first name doesn\'t look right!', 'Error');
        }
        return null;
    }

    private function validateLastName($lastName): ?ResponseDTO
    {
        if (empty($lastName) || !is_string($lastName)) {
            return new ResponseDTO('Last name cannot be empty and must be a string.', 'Error');
        }

        $lastName = $this->trim($lastName);
        if (strlen($lastName) >= 30 || strlen($lastName) <= 2 ||
            !preg_match('/^[\p{L}\s-]+$/u', $lastName)) {
            return new ResponseDTO('Oops, your last name doesn\'t look right!', 'Error');
        }
        return null;
    }

    private function validateCity($city): ?ResponseDTO
    {
        if (empty($city) || !is_string($city)) {
            return new ResponseDTO('City cannot be empty and must be a string.', 'Error');
        }

        $city = $this->trim($city);
        if (strlen($city) >= 20 || strlen($city) <= 2 ||
            !preg_match('/^[a-zA-Z\s-]+$/', $city)) {
            return new ResponseDTO('Oops, your City doesn\'t look right!', 'Error');
        }
        return null;
    }

    private function validateState($state): ?ResponseDTO
    {
        if (empty($state) || !is_string($state)) {
            return new ResponseDTO('State cannot be empty and must be a string.', 'Error');
        }

        $state = $this->trim($state);
        if (strlen($state) >= 20 || strlen($state) <= 2 ||
            !preg_match('/^[a-zA-Z\s-]+$/', $state)) {
            return new ResponseDTO('Oops, your State doesn\'t look right!', 'Error');
        }
        return null;
    }

    private function validatePhoneNumber($phoneNumber): ?ResponseDTO
    {
        if (empty($phoneNumber) || !is_string($phoneNumber)) {
            return new ResponseDTO('Phone number cannot be empty and must be a string.', 'Error');
        }

        $phoneNumber = $this->trim($phoneNumber);

        // Basic international phone number format (e.g., +1234567890, 00441234567890)
        $phonePattern = '/^\+?[0-9]{1,4}?[0-9]{6,14}$/';

        if (!preg_match($phonePattern, $phoneNumber)) {
            return new ResponseDTO('Oops, your phone number doesn\'t look right!', 'Error');
        }
        return null;
    }

    private function validateZip($zip): ?ResponseDTO
    {
        if (empty($zip) || !is_string($zip)) {
            return new ResponseDTO('Zip code cannot be empty and must be a string.', 'Error');
        }

        $zip = $this->trim($zip);
        if (!preg_match('/^\d{4,6}$/', $zip)) {
            return new ResponseDTO('Oops, your Postcode doesn\'t look right!', 'Error');
        }
        return null;
    }

    private function validateAddress($address): ?ResponseDTO
    {
        if (empty($address) || !is_string($address)) {
            return new ResponseDTO('Address cannot be empty and must be a string.', 'Error');
        }

        $address = $this->trim($address);
        if (strlen($address) >= 20 || strlen($address) <= 2 ||
            !preg_match('/^[a-zA-Z0-9\s.-]+$/', $address)) {
            return new ResponseDTO('Oops, your Address doesn\'t look right!', 'Error');
        }
        return null;
    }

    public function trim(string $value): string
    {
        return trim($value);
    }
}