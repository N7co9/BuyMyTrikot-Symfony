<?php

namespace App\Global\Persistence\DTO;

class ResponseDTO
{
    public function __construct(
        public readonly string $message,
        public readonly string $type
    )
    {
    }

    public function getMessage()
    {
        return $this->message;
    }
    public function getType()
    {
        return $this->type;
    }
}