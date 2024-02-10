<?php
declare(strict_types=1);

namespace App\Global\DTO;
class BillingDTO
{
    public string $firstName;
    public string  $lastName;
    public string  $address;
    public string  $city;
    public string  $country;
    public string  $region;
    public string  $postalCode;
    public string  $phone;
    public int  $userId;

    public function __construct($firstName, $lastName, $address, $city, $country, $region, $postalCode, $phone, $userId)
    {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->address = $address;
        $this->city = $city;
        $this->country = $country;
        $this->region = $region;
        $this->postalCode = $postalCode;
        $this->phone = $phone;
        $this->userId = $userId ?? 0;
    }

    /**
     * @return mixed
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * @param mixed $userId
     */
    public function setUserId($userId): void
    {
        $this->userId = $userId;
    }

    public function getFirstName()
    {
        return $this->firstName;
    }

    public function getLastName()
    {
        return $this->lastName;
    }

    public function getAddress()
    {
        return $this->address;
    }

    public function getCity()
    {
        return $this->city;
    }

    public function getCountry()
    {
        return $this->country;
    }

    public function getRegion()
    {
        return $this->region;
    }

    public function getPostalCode()
    {
        return $this->postalCode;
    }

    public function getPhone()
    {
        return $this->phone;
    }
}