<?php
declare(strict_types=1);

namespace App\Components\UserSettings\Business\Model\EmailAddressModification;

use App\Components\UserSettings\Persistence\EmailVerificationEntityManager;
use App\Components\UserSettings\Persistence\TemporaryMailsRepository;
use App\Components\User\Business\UserBusinessFacadeInterface;
use App\Entity\TemporaryMails;
use App\Entity\User;
use App\Global\DTO\ResponseDTO;
use App\Global\DTO\UserDTO;
use App\Global\Service\MailerInterface\MailService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\RouterInterface;

class EmailModificationWrite
{
    public function __construct
    (
        private readonly TemporaryMailsRepository       $temporaryMailsRepository,
        private readonly EmailVerificationEntityManager $emailVerificationEntityManager,
        private readonly EmailModificationRead          $verificationMailReader,
        private readonly MailerInterface                $symfonyMailer,
        private readonly MailService                    $mailService,
        private readonly UserBusinessFacadeInterface    $userBusinessFacade,
    )
    {
    }

    public function saveTemporaryEmail(UserDTO $userDTO): void
    {
        $tempMail = $this->temporaryMailsRepository->findOneByOwnedBy($userDTO->id);
        if ($tempMail === null) {
            $tempMail = new TemporaryMails();
            $tempMail->setEmail($userDTO->email);
            $tempMail->setOwnedBy($userDTO->id);
        }

        $tempMail->setEmail($userDTO->email);
        $this->emailVerificationEntityManager->persistNewTemporaryEmail($tempMail);
    }

    public function sendVerificationEmail(RouterInterface $router, Request $request): void
    {
        $userDTO = $this->userBusinessFacade->fetchUserInformationFromAuthentication($request)->content;

        $verificationUrl = $this->verificationMailReader->generateUrl($router, $userDTO);
        $unverifiedUserDTO = $this->verificationMailReader->addNewEmailToUserDTO($request, $userDTO);
        $this->mailService->sendVerificationEmail($this->symfonyMailer, $unverifiedUserDTO ?? null, $verificationUrl);

        $this->saveTemporaryEmail($unverifiedUserDTO);
    }

    public function saveNewVerifiedEmail(Request $request): User
    {
        $newUserEntity = $this->verificationMailReader->verifyToken($request);
        $this->emailVerificationEntityManager->persistNewVerifiedEmail($newUserEntity);
        return $newUserEntity;
    }

    public function receiveAndPersistNewEmail(Request $request): ResponseDTO
    {
        try {
            $user = $this->saveNewVerifiedEmail($request);
            return new ResponseDTO($user->getUserIdentifier(), true);
        } catch (\Exception $exception) {
            return new ResponseDTO($exception->getMessage(), false);
        }
    }
}