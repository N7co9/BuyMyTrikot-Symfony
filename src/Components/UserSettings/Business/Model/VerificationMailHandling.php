<?php
declare(strict_types=1);

namespace App\Components\UserSettings\Business\Model;

use App\Components\User\Persistence\UserRepository;
use App\Global\DTO\UserDTO;
use App\Global\Service\MailerInterface\MailService;
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
        private readonly EntityManagerInterface $entityManager
    )
    {
    }

    private function generateUrl(RouterInterface $router, UserDTO $userDTO): string
    {
        $verificationToken = $userDTO->verificationToken;
        return $router->generate('app_email_processing', ['verificationToken' => $verificationToken], UrlGeneratorInterface::ABSOLUTE_URL);
    }

    public function sendVerificationEmail(RouterInterface $router, UserDTO $userDTO): void
    {
        $verificationUrl = $this->generateUrl($router, $userDTO);
        $this->mailService->sendVerificationEmail($this->symfonyMailer, $userDTO, $verificationUrl);
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