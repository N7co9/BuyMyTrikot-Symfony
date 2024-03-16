<?php
declare(strict_types=1);

namespace App\Components\Settings\Business\Model\UsernameModification;

use App\Components\Settings\Communication\Form\UsernameInputValidation;
use App\Global\DTO\ResponseDTO;
use Symfony\Component\HttpFoundation\Request;

class UsernameModificationRead
{
    public function __construct
    (
        private readonly UsernameInputValidation $usernameInputValidation
    )
    {
    }

    public function fetchNewUsername(Request $request): ResponseDTO
    {
        try {
            $data = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);
            return $this->usernameInputValidation->validateNewUsername($data['username']);
        } catch (\Exception $exception) {
            return new ResponseDTO($exception, false);
        }
    }
}