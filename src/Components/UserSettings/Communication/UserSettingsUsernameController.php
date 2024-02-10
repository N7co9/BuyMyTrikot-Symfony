<?php
declare(strict_types=1);

namespace App\Components\UserSettings\Communication;

use App\Components\UserSettings\Business\UserSettingsBusinessFacade;
use App\Symfony\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserSettingsUsernameController extends AbstractController
{
    public function __construct
    (
        private readonly UserSettingsBusinessFacade $userSettingsBusinessFacade
    )
    {
    }
    #[Route('/settings/update-username', name: 'app_update_username')]
    public function requestNewUsername(Request $request) : Response
    {
        $user = $this->getLoggingUser();

        $res = $this->userSettingsBusinessFacade->setNewUsername($request, $user);

        return $this->render('user_settings/index.html.twig', ['user' => $user, 'usernameResponse' => $res]);

    }
}