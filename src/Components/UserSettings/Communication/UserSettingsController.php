<?php
declare(strict_types=1);

namespace App\Components\UserSettings\Communication;

use App\Symfony\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserSettingsController extends AbstractController
{
    #[Route('/settings/', name: 'app_user_settings')]
    public function indexSettings(): Response
    {
        $user = $this->getLoggingUser();
        return $this->render('user_settings/index.html.twig', ['user' => $user]);
    }
}