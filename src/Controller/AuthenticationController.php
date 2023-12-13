<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\LoginFormType;
use App\Service\Validation\UserLoginValidation;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class AuthenticationController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(UserLoginValidation $loginValidation, Request $request,
                          SessionInterface $session  ): Response
    {
        $form = $this->createForm(LoginFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $formData = $form->getData();
            $response = $loginValidation->authenticate($formData, $session);
            if ($response->getType() === 'OK') {
                return $this->redirectToRoute('app_homepage');
            }
        }
        return $this->render('security/login.html.twig', [
            'form' => $form->createView(),
            'response' => $response ?? ''
        ]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        // The logout logic is handled by Symfony, this method can be empty.
        // You can redirect to the homepage or another page if you want.
    }
}
