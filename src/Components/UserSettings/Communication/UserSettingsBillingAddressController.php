<?php
declare(strict_types=1);

namespace App\Components\UserSettings\Communication;

use App\Components\UserSettings\Business\UserSettingsBusinessFacadeInterface;
use App\Symfony\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserSettingsBillingAddressController extends AbstractController
{
    public function __construct
    (
        private readonly UserSettingsBusinessFacadeInterface $userSettingsBusinessFacade
    )
    {
    }

    #[Route('/settings/update-billing', name: 'app_update_billing')]
    public function requestNewBillingAddress(Request $request): Response
    {
        $res = $this->userSettingsBusinessFacade->setNewBillingAddress($request);

        return $this->json(
            [
                'response' => $res
            ]
        );
    }

    #[Route('/settings/fetch-billing', name: 'app_fetch_billing')]
    public function fetchCurrentBillingAddress(Request $request): Response
    {

        $res = $this->userSettingsBusinessFacade->retrieveBillingAddress($request);

        return $this->json(
                $res
        );
    }}