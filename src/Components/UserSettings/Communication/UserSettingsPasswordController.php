<?php
declare(strict_types=1);

namespace App\Components\UserSettings\Communication;

use App\Components\UserSettings\Business\UserSettingsBusinessFacadeInterface;
use App\Symfony\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserSettingsPasswordController extends AbstractController
{

    public function __construct
    (
        private readonly UserSettingsBusinessFacadeInterface $userSettingsBusinessFacade
    )
    {
    }

    #[Route('/settings/update-password', name: 'app_update_password')]
    public function requestNewPassword(Request $request): Response
    {
        $user = $this->getLoggingUser();

        $res = $this->userSettingsBusinessFacade->setNewPassword($user, $request);

        return $this->render('user_settings/index.html.twig', ['user' => $user, 'passwordResponse' => $res]);
    }
}