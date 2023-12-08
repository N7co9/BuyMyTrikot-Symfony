<?php

namespace App\Service\Validation;

use App\Entity\Orders;
use App\Model\DTO\ResponseDTO;

class OrderFlowValidation
{
    public function validate(Orders $order): array
    {
        $responses = [
            'firstName' => $this->validateFirstName($order->getFirstName()),
            'lastName' => $this->validateLastName($order->getLastName()),
            'city' => $this->validateCity($order->getCity()),
            'zip' => $this->validateZip($order->getZip()),
            'address' => $this->validateAddress($order->getAddress()),
            'region' => $this->validateState($order->getState()),
            'phoneNumber' => $this->validatePhoneNumber($order->getPhoneNumber())
        ];
        return $responses;
    }

    private function validateFirstName($firstName): ?ResponseDTO
    {
        $firstName = $this->trim($firstName);
        if (strlen($firstName) >= 30 || strlen($firstName) <= 2 ||
            !preg_match('/^[a-zA-Z\s-]+$/', $firstName)) {
            return new ResponseDTO('Oops, your first name doesn\'t look right!', 'Error');
        }
        return null;
    }

    private function validateLastName($lastName): ?ResponseDTO
    {
        $lastName = $this->trim($lastName);
        if (strlen($lastName) >= 30 || strlen($lastName) <= 2 ||
            !preg_match('/^[a-zA-Z\s-]+$/', $lastName)) {
            return new ResponseDTO('Oops, your last name doesn\'t look right!', 'Error');
        }
        return null;
    }

    private function validateCity($city): ?ResponseDTO
    {
        $city = $this->trim($city);
        if (strlen($city) >= 20 || strlen($city) <= 2 ||
            !preg_match('/^[a-zA-Z\s-]+$/', $city)) {
            return new ResponseDTO('Oops, your City doesn\'t look right!', 'Error');
        }
        return null;
    }

    private function validateState($state): ?ResponseDTO
    {
        $state = $this->trim($state);
        if (strlen($state) >= 20 || strlen($state) <= 2 ||
            !preg_match('/^[a-zA-Z\s-]+$/', $state)) {
            return new ResponseDTO('Oops, your State doesn\'t look right!', 'Error');
        }
        return null;
    }

    private function validatePhoneNumber($phoneNumber): ?ResponseDTO
    {
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
        $zip = $this->trim($zip);
        if (!preg_match('/^\d{4,6}$/', $zip)) {
            return new ResponseDTO('Oops, your Postcode doesn\'t look right!', 'Error');
        }
        return null;
    }

    private function validateAddress($address): ?ResponseDTO
    {
        $address = $this->trim($address);
        if (strlen($address) >= 20 || strlen($address) <= 2 ||
            !preg_match('/^[a-zA-Z\s-]+$/', $address)) {
            return new ResponseDTO('Oops, your Address doesn\'t look right!', 'Error');
        }
        return null;
    }

    public function trim(string $value): string
    {
        return trim($value);
    }
}