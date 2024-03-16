<?php
declare(strict_types=1);

namespace App\Components\Settings\Communication;

use App\Components\Settings\Business\UserSettingsBusinessFacadeInterface;
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
        $res = $this->userSettingsBusinessFacade->setNewPassword($request);

        return $this->json(
            [
                'response' => $res
            ]
        );
    }
}