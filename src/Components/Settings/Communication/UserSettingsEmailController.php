<?php
declare(strict_types=1);

namespace App\Components\Settings\Communication;

use App\Components\Settings\Business\UserSettingsBusinessFacadeInterface;
use App\Symfony\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserSettingsEmailController extends AbstractController
{
    public function __construct
    (
        private readonly UserSettingsBusinessFacadeInterface $userSettingsBusinessFacade
    )
    {
    }

    #[Route('/settings/update-email', name: 'app_email_verification')]
    public function sendVerificationEmail(Request $request): Response
    {
        try {
           $this->userSettingsBusinessFacade->sendVerificationEmail($request);
            return $this->json(
                [
                    'sent' => true,
                ]
            );
        } catch (\Exception $exception) {
            return $this->json(
                [
                    'sent' => false,
                    'exception' => $exception
                ]
            );
        }
    }

    #[Route('/settings/update-email/{verificationToken}', name: 'app_email_processing', defaults: ['verificationToken' => ''])]
    public function handleVerificationEmail(Request $request): Response
    {
        $response = $this->userSettingsBusinessFacade->receiveAndPersistNewEmail($request);
        return $this->json(
            [
                'response' => $response,
            ]
        );
    }
}