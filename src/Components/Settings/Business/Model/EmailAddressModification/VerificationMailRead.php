<?php
declare(strict_types=1);

namespace App\Components\Settings\Business\Model\EmailAddressModification;

use App\Components\Settings\Persistence\TemporaryMailsRepository;
use App\Components\User\Business\UserBusinessFacadeInterface;
use App\Entity\User;
use App\Global\DTO\UserDTO;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;

class VerificationMailRead
{
    public function __construct
    (
        private readonly UserBusinessFacadeInterface $userBusinessFacade,
        private readonly TemporaryMailsRepository    $temporaryMailsRepository
    )
    {
    }

    public function generateUrl(RouterInterface $router, UserDTO $userDTO): string
    {
        $verificationToken = $userDTO->verificationToken;
        $route = $router->generate('app_email_processing', ['verificationToken' => $verificationToken], UrlGeneratorInterface::ABSOLUTE_URL);

        $url = 'http://localhost:3000/user/' . parse_url($route, PHP_URL_PATH);
        return $url;
    }

    public function addNewEmailToUserDTO(Request $request, UserDTO $userDTO): UserDTO
    {
        $data = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);
        $userDTO->email = $data['email'];

        return $userDTO;
    }

    public function verifyToken(Request $request): User
    {
        $user = $this->userBusinessFacade->fetchUserEntityFromAuthentication($request);

        if ($user->success) {
            $user = $user->content;
            $newEmail = $this->temporaryMailsRepository->findEmailByOwnedBy($user->id);

            $user?->setEmail($newEmail);
            $user?->setIsVerified(true);
            $user->setVerificationToken(bin2hex(random_bytes(16)));
        }
        return $user;
    }
}