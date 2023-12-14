<?php

namespace App\Components\Authentication\Communication;

use App\Components\Authentication\Business\Validation\UserLoginValidation;
use App\Components\Authentication\Presentation\Form\LoginFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class AuthenticationController extends AbstractController
{
    public string $flag;
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

        return $this->render('/security/login.html.twig', [
            'form' => $form->createView(),
            'response' => $response ?? ''
        ]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
    }
}
