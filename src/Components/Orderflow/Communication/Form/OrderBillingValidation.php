<?php

namespace App\Components\Orderflow\Communication\Form;

use App\Global\DTO\OrderDTO;
use App\Global\DTO\ResponseDTO;

class OrderBillingValidation
{
    public function validate(OrderDTO $order): array
    {
        return array_filter([
            'firstName' => $this->validateName($order->firstName, 'First name'),
            'lastName' => $this->validateName($order->lastName, 'Last name'),
            'city' => $this->validateLocation($order->city, 'City'),
            'state' => $this->validateLocation($order->state, 'State'),
            'phoneNumber' => $this->validatePhoneNumber($order->phoneNumber),
            'zip' => $this->validateZip($order->zip),
            'region' => $this->validateRegion($order->state),
            'address' => $this->validateAddress($order->address),
        ]);
    }

    private function validateName($value, $fieldName): ?ResponseDTO
    {
        return $this->validateField($value, $fieldName, 2, 30, '/^[\p{L}\s-]+$/u', "$fieldName doesn't look right!");
    }

    private function validateLocation($value, $fieldName): ?ResponseDTO
    {
        return $this->validateField($value, $fieldName, 2, 20, '/^[a-zA-Z\s-]+$/', "$fieldName doesn't look right!");
    }

    private function validatePhoneNumber($phoneNumber): ?ResponseDTO
    {
        return $this->validateField($phoneNumber, 'Phone number', 6, 14, '/^\+?[0-9]{1,4}?[0-9]{6,14}$/', "Phone number doesn't look right!");
    }

    private function validateZip($zip): ?ResponseDTO
    {
        return $this->validateField($zip, 'Zip code', 4, 6, '/^\d{4,6}$/', "Postcode doesn't look right!", false);
    }

    private function validateAddress($address): ?ResponseDTO
    {
        return $this->validateField($address, 'Address', 2, 100, '/^[a-zA-Z0-9\s.-]+$/', "Address doesn't look right!");
    }

    private function validateRegion($region): ?ResponseDTO
    {
        return $this->validateField($region, 'Region', 2, 100, '/^[a-zA-Z0-9\s.-]+$/', "Region doesn't look right!");
    }

    private function validateField($value, $fieldName, $minLength, $maxLength, $pattern, $errorMessage, $checkLength = true): ?ResponseDTO
    {
        if (empty($value) || !is_string($value)) {
            return new ResponseDTO("$fieldName cannot be empty and must be a string.", false);
        }

        $value = $this->trim($value);

        if (($checkLength && (strlen($value) < $minLength || strlen($value) > $maxLength)) || !preg_match($pattern, $value)) {
            return new ResponseDTO($errorMessage, false);
        }

        return null;
    }

    public function trim(string $value): string
    {
        return trim($value);
    }
}
