<?php
declare(strict_types=1);

namespace App\Components\Settings\Business\Model\EmailAddressModification;

use App\Components\Settings\Business\Model\EmailAddressModification\VerificationMailRead;
use App\Components\Settings\Business\Model\EmailAddressModification\VerificationMailWrite;
use App\Global\DTO\ResponseDTO;
use App\Global\Service\MailerInterface\MailService;
use App\Global\Service\Mapping\UserMapper;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\RouterInterface;

class VerificationMailHandling
{
    public function __construct
    (
        private readonly VerificationMailRead  $verificationMailReader,
        private readonly VerificationMailWrite $verificationMailWriter,
        private readonly MailerInterface       $symfonyMailer,
        private readonly MailService           $mailService,
        private readonly UserMapper            $mapper,
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