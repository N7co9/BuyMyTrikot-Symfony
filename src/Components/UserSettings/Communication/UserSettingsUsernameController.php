<?php
declare(strict_types=1);

namespace App\Components\UserSettings\Communication;

use App\Components\UserSettings\Business\UserSettingsBusinessFacadeInterface;
use App\Symfony\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

class UserSettingsUsernameController extends AbstractController
{
    public function __construct
    (
        private readonly UserSettingsBusinessFacadeInterface $userSettingsBusinessFacade
    )
    {}

    #[Route('/settings/update-username', name: 'app_update_username', methods: 'POST')]
    public function requestNewUsername(Request $request): Response
    {
        $res = $this->userSettingsBusinessFacade->setNewUsername($request);

        return $this->json(
            [
                'response' => $res
            ]
        );
    }
}