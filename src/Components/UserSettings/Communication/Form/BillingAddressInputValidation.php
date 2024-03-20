<?php
declare(strict_types=1);

namespace App\Components\UserSettings\Communication\Form;

use App\Global\DTO\BillingDTO;
use App\Global\DTO\ResponseDTO;

class BillingAddressInputValidation
{
    private const VALIDATION_RULES = [
        'firstName' => ['maxLength' => 30, 'minLength' => 2, 'pattern' => '/^[\p{L}\s-]+$/u', 'message' => 'First name'],
        'lastName' => ['maxLength' => 30, 'minLength' => 2, 'pattern' => '/^[\p{L}\s-]+$/u', 'message' => 'Last name'],
        'city' => ['maxLength' => 20, 'minLength' => 2, 'pattern' => '/^[a-zA-Z\s-]+$/', 'message' => 'City'],
        'region' => ['maxLength' => 20, 'minLength' => 2, 'pattern' => '/^[a-zA-Z\s-]+$/', 'message' => 'State'],
        'address' => ['maxLength' => 20, 'minLength' => 2, 'pattern' => '/^[a-zA-Z0-9\s.-]+$/', 'message' => 'Address'],
        'postalCode' => ['pattern' => '/^\d{4,6}$/', 'message' => 'Postcode'],
        'phone' => ['pattern' => '/^\+?[0-9]{1,4}?[0-9]{6,14}$/', 'message' => 'Phone number'],
    ];

    public function validate(BillingDTO $order): array
    {
        $errorCheck = [];
        foreach (self::VALIDATION_RULES as $field => $rules) {
            $method = "validate" . ucfirst($field);
            if (method_exists($this, $method)) {
                $errorCheck[$field] = $this->$method($order->$field, $rules);
            }
        }

        return array_filter($errorCheck);
    }

    private function validateGeneric($value, array $rules): ?ResponseDTO
    {
        if (empty($value) || !is_string($value)) {
            return new ResponseDTO("{$rules['message']} cannot be empty and must be a string.", false);
        }

        $value = $this->trim($value);

        if ((isset($rules['minLength']) && strlen($value) < $rules['minLength']) ||
            (isset($rules['maxLength']) && strlen($value) > $rules['maxLength']) ||
            (isset($rules['pattern']) && !preg_match($rules['pattern'], $value))) {
            return new ResponseDTO("Oops, your {$rules['message']} doesn't look right!", false);
        }

        return null;
    }

    public function trim(string $value): string
    {
        return trim($value);
    }

    private function validateFirstName($value, $rules) { return $this->validateGeneric($value, $rules); }
    private function validateLastName($value, $rules) { return $this->validateGeneric($value, $rules); }
    private function validateCity($value, $rules) { return $this->validateGeneric($value, $rules); }
    private function validateRegion($value, $rules) { return $this->validateGeneric($value, $rules); }
    private function validateAddress($value, $rules) { return $this->validateGeneric($value, $rules); }
    private function validatePostalCode($value, $rules) { return $this->validateGeneric($value, $rules); }
    private function validatePhone($value, $rules) { return $this->validateGeneric($value, $rules); }
}
