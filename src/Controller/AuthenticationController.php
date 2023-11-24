<?php

namespace App\Controller;


use App\Service\Validation\UserLoginValidation;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class AuthenticationController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(UserLoginValidation $loginValidation, Request $request, SessionInterface $session): Response
    {
        $response = $loginValidation->authenticate($request, $session);
        if ($response->getType() === 'OK') {
            // Pass a flag to the template
            return $this->render('security/login.html.twig', [
                'response' => $response,
                'redirect' => true
            ]);
        }

        return $this->render('security/login.html.twig', [
            'response' => $response,
            'redirect' => false
        ]);
    }
    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(Request $request): Response
    {
        $session = $request->getSession();
        $session->clear();
        return $this->redirectToRoute('app_homepage');
    }
}
