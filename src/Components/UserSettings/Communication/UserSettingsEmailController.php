<?php
declare(strict_types=1);

namespace App\Components\UserSettings\Communication;

use App\Components\UserSettings\Business\UserSettingsBusinessFacade;
use App\Symfony\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserSettingsEmailController extends AbstractController
{
    public function __construct
    (
        private readonly UserSettingsBusinessFacade $userSettingsBusinessFacade
    )
    {
    }

    #[Route('/settings/update-email', name: 'app_email_verification')]
    public function sendVerificationEmail(Request $request): Response
    {
        $userDTO = $this->getLoggingUser();
        $userDTO->email = $request->get('email');
        $this->userSettingsBusinessFacade->addUnverifiedEmailToSession($request);
        $this->userSettingsBusinessFacade->sendVerificationEmail($userDTO);
        return $this->render('user_settings/index.html.twig', ['sent' => true, 'user' => $userDTO]);
    }

    #[Route('/settings/update-email/{verificationToken}', name: 'app_email_processing', defaults: ['verificationToken' => ''])]
    public function handleVerificationEmail(string $verificationToken, Request $request): Response
    {
        $userDTO = $this->getLoggingUser();
        $response = $this->userSettingsBusinessFacade->verifyToken($verificationToken, $request);
        return $this->render('user_settings/index.html.twig', ['user' => $userDTO, 'emailResponse' => $response]);
    }
}