<?php

namespace App\Global\DTO;

class ResponseDTO
{
    public function __construct(
        public readonly string $message,
        public readonly string $type
    )
    {
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function getType(): string
    {
        return $this->type;
    }
}