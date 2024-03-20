<?php
declare(strict_types=1);

namespace App\Components\UserSettings\Business\Model\PasswordModification;

use App\Components\User\Business\UserBusinessFacadeInterface;
use App\Entity\User;
use App\Global\DTO\ResponseDTO;
use Symfony\Component\HttpFoundation\Request;

class PasswordModificationRead
{
    public function __construct
    (
        private readonly UserBusinessFacadeInterface $userBusinessFacade
    )
    {
    }

    public function checkInputPassword(User $user, string $formInputOldPassword): bool
    {
        $hashedPassword = $user->getPassword();
        return password_verify($formInputOldPassword, $hashedPassword);
    }

    public function verifyNewPassword($formInputPassword): ResponseDTO
    {
        if (
            empty($formInputPassword) ||
            !preg_match('@[A-Z]@', $formInputPassword) ||
            !preg_match('@[a-z]@', $formInputPassword) ||
            !preg_match('@\d@', $formInputPassword) ||
            !preg_match('@\W@', $formInputPassword) ||
            (strlen($formInputPassword) <= 6)
        ) {
            return new ResponseDTO('Your new Password doesn\'t match the requirements.', false);
        }
        return new ResponseDTO('A valid Password was submitted', true);
    }

    public function fetchNewPasswordInformation(Request $request): ResponseDTO
    {
        $user = $this->userBusinessFacade->fetchUserEntityFromAuthentication($request);
        $data = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);

        $formInputOldPassword = $data['old_password'];
        $formInputNewPassword = $data['new_password'];

        if ($user->success && ($formInputNewPassword) !== null) {
            return new ResponseDTO(
                [
                    'user' => $user->content,
                    'formInputOldPassword' => $formInputOldPassword,
                    'formInputNewPassword' => $formInputNewPassword
                ],
                true
            );
        }
        return new ResponseDTO('An Exception occurred while fetching Information about the new Password', false);
    }
}