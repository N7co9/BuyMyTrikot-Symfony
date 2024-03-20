<?php
declare(strict_types=1);

namespace App\Components\UserSettings\Business\Model\PasswordModification;

use App\Global\DTO\ResponseDTO;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class PasswordModificationWrite
{
    public function __construct
    (
        private readonly EntityManagerInterface   $entityManager,
        private readonly PasswordModificationRead $passwordModificationRead,
    )
    {
    }


    public function setNewPassword(Request $request): ResponseDTO
    {
        try {
            $user = $this->passwordModificationRead->fetchNewPasswordInformation($request)->content['user'];
            $formInputNewPassword = $this->passwordModificationRead->fetchNewPasswordInformation($request)->content['formInputNewPassword'];
            $formInputOldPassword = $this->passwordModificationRead->fetchNewPasswordInformation($request)->content['formInputOldPassword'];
        } catch (\Exception $exception) {
            return new ResponseDTO($exception, false);
        }

        $isOldPasswordCorrect = $this->passwordModificationRead->checkInputPassword($user, $formInputOldPassword);

        if ($isOldPasswordCorrect && $user !== null) {
            $response = $this->passwordModificationRead->verifyNewPassword($formInputNewPassword);
            if ($response->success) {
                $user->setPassword(password_hash($formInputNewPassword, PASSWORD_DEFAULT));
                $this->entityManager->persist($user);
                $this->entityManager->flush();
                return new ResponseDTO('Password changed successfully.', true);
            }
            return $response;
        }
        return new ResponseDTO('Your current Password is incorrect. Please try again.', false);
    }

}