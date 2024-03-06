<?php
declare(strict_types=1);

namespace App\Components\UserSettings\Business\Model;

use App\Components\Authentication\Persistence\ApiTokenRepository;
use App\Components\User\Persistence\UserRepository;
use App\Components\UserSettings\Business\Model\Read\VerificationMailReader;
use App\Components\UserSettings\Business\Model\Write\VerificationMailWriter;
use App\Entity\TemporaryMails;
use App\Entity\User;
use App\Global\DTO\ResponseDTO;
use App\Global\DTO\UserDTO;
use App\Global\Service\MailerInterface\MailService;
use App\Global\Service\Mapping\Mapper;
use App\Global\Service\Mapping\UserMapper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;

class VerificationMailHandling
{
    public function __construct
    (
        private readonly VerificationMailReader $verificationMailReader,
        private readonly VerificationMailWriter $verificationMailWriter,
        private readonly MailerInterface        $symfonyMailer,
        private readonly MailService            $mailService,
        private readonly UserMapper             $mapper,
    )
    {
    }


    public function sendVerificationEmail(RouterInterface $router, Request $request): void
    {
        $user = $this->verificationMailReader->getUserFromToken($request);
        $userDTO = $this->mapper->mapEntityToDto($user);

        $verificationUrl = $this->verificationMailReader->generateUrl($router, $userDTO);
        $unverifiedUserDTO = $this->verificationMailReader->addNewEmailToUserDTO($request, $userDTO);
        $this->mailService->sendVerificationEmail($this->symfonyMailer, $unverifiedUserDTO ?? null, $verificationUrl);

        $this->verificationMailWriter->saveTemporaryEmail($unverifiedUserDTO);
    }

    public function receiveAndPersistNewEmail(Request $request) : ResponseDTO
    {
        try {
            $user = $this->verificationMailWriter->saveNewVerifiedEmail($request);
            return new ResponseDTO($user->getUserIdentifier(), 'OK');
        }
        catch (\Exception $exception)
        {
            return new ResponseDTO($exception->getMessage(), 'Exception');
        }
    }

}