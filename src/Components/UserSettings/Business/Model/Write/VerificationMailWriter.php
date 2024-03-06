<?php
declare(strict_types=1);

namespace App\Components\UserSettings\Business\Model\Write;

use App\Components\UserSettings\Business\Model\Read\VerificationMailReader;
use App\Components\UserSettings\Persistence\TemporaryMailsRepository;
use App\Entity\TemporaryMails;
use App\Entity\User;
use App\Global\DTO\UserDTO;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use function PHPUnit\Framework\isInstanceOf;

class VerificationMailWriter
{
    public function __construct
    (
        private readonly TemporaryMailsRepository $temporaryMailsRepository,
        private readonly EntityManagerInterface $entityManager,
        private readonly VerificationMailReader $verificationMailReader,
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