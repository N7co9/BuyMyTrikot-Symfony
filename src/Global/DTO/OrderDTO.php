<?php

namespace App\Global\DTO;

class OrderDTO
{
    public ?int $id = null;
    public string $email = '';
    public string $firstName = '';
    public string $lastName = '';
    public string $address = '';
    public string $city = '';
    public string $state = '';
    public string $zip = '';
    public string $phoneNumber = '';
    public string $deliveryMethod = '';
    public float $due = 0.0;
    public string $shipping = '';

    public array $items = [];
}