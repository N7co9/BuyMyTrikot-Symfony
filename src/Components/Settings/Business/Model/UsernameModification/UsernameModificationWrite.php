<?php
declare(strict_types=1);

namespace App\Components\Settings\Business\Model\UsernameModification;

use App\Components\User\Business\UserBusinessFacadeInterface;
use App\Components\Settings\Persistence\UsernameEntityManager;
use App\Global\DTO\ResponseDTO;
use Symfony\Component\HttpFoundation\Request;

class UsernameModificationWrite
{
    public function __construct
    (
        private readonly UsernameModificationRead    $usernameModificationRead,
        private readonly UserBusinessFacadeInterface $userBusinessFacade,
        private readonly UsernameEntityManager       $usernameEntityManager,
    )
    {
    }

    public function setNewUsername(Request $request): ResponseDTO
    {
        $response = $this->usernameModificationRead->fetchNewUsername($request);
        $user = $this->userBusinessFacade->fetchUserInformationFromAuthentication($request);

        if ($response->success && $user->success) {
            $user = $this->userBusinessFacade->fetchUserEntityFromAuthentication($request)->content;
            $user->setUsername($response->content);
            $this->usernameEntityManager->persistNewUsername($user);
            return new ResponseDTO('Username set successfully', true);
        }

        return $response;
    }
}