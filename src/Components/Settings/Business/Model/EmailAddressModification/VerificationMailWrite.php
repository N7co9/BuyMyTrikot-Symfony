<?php
declare(strict_types=1);

namespace App\Components\Settings\Business\Model\EmailAddressModification;

use App\Components\Settings\Persistence\TemporaryMailsRepository;
use App\Entity\TemporaryMails;
use App\Entity\User;
use App\Global\DTO\UserDTO;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class VerificationMailWrite
{
    public function __construct
    (
        private readonly TemporaryMailsRepository $temporaryMailsRepository,
        private readonly EntityManagerInterface   $entityManager,
        private readonly VerificationMailRead     $verificationMailReader,
    )
    {
    }

    public function saveTemporaryEmail(UserDTO $userDTO) : void
    {
        $tempMail = $this->temporaryMailsRepository->findOneByOwnedBy($userDTO->id);
        if ($tempMail === null)
        {
            $tempMail = new TemporaryMails();
            $tempMail->setEmail($userDTO->email);
            $tempMail->setOwnedBy($userDTO->id);
        }

        $tempMail->setEmail($userDTO->email);
        $this->entityManager->persist($tempMail);
        $this->entityManager->flush();
    }

    public function saveNewVerifiedEmail(Request $request) : User
    {
        $newUserEntity = $this->verificationMailReader->verifyToken($request);

        $this->entityManager->persist($newUserEntity);
        $this->entityManager->flush();
        return $newUserEntity;
    }
}