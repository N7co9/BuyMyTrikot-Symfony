<?php
declare(strict_types=1);

namespace App\Components\UserSettings\Business\Model;

use App\Components\Authentication\Persistence\ApiTokenRepository;
use App\Components\User\Persistence\UserRepository;
use App\Global\DTO\UserDTO;
use App\Global\Service\MailerInterface\MailService;
use App\Global\Service\Mapping\Mapper;
use App\Global\Service\Mapping\UserMapper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;

class VerificationMailHandling
{
    public function __construct
    (
        private readonly MailerInterface        $symfonyMailer,
        private readonly MailService            $mailService,
        private readonly UserRepository         $userRepository,
        private readonly UserMapper             $mapper,
        private readonly ApiTokenRepository     $apiTokenRepository,
        private readonly EntityManagerInterface $entityManager
    )
    {
    }

    private function generateUrl(RouterInterface $router, UserDTO $userDTO): string
    {
        $verificationToken = $userDTO->verificationToken;
        return $router->generate('app_email_processing', ['verificationToken' => $verificationToken], UrlGeneratorInterface::ABSOLUTE_URL);
    }

    public function sendVerificationEmail(RouterInterface $router, Request $request): void
    {
        $user = $this->apiTokenRepository->findUserByToken($request->headers->get('Authorization'));
        if ($user) {
            $userDTO = $this->mapper->mapEntityToDto($user);
        }
        $verificationUrl = $this->generateUrl($router, $userDTO ?? null);
        $data = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);
        $userDTO->email = $data['email'];
        $this->mailService->sendVerificationEmail($this->symfonyMailer, $userDTO ?? null, $verificationUrl);
    }

    public function verifyToken(Request $request, string $token): ?bool
    {
        $user = $this->userRepository->findOneByVerificationToken($token);
        $newEmail = $request->getSession()->get('unverifiedEmail');
        $request->getSession()->remove('unverifiedEmail');

        if ($user) {
            $user?->setEmail($newEmail);
            $user?->setIsVerified(true);
            $user->setVerificationToken(bin2hex(random_bytes(16)));
            $this->entityManager->persist($user);
            $this->entityManager->flush();
        }

        return $user->getIsVerified();
    }
}