<?php

namespace App\Global\DTO;

class ResponseDTO
{
    public function __construct(
        public readonly mixed $content,
        public readonly bool $success,
    )
    {
    }
}