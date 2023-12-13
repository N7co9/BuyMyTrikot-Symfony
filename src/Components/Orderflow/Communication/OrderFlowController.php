<?php

namespace App\Components\Orderflow\Communication;

use App\Components\Orderflow\Business\Validation\OrderFlowValidation;
use App\Components\Orderflow\Persistence\OrderFlowEntityManager;
use App\Components\Orderflow\Persistence\OrdersRepository;
use App\Components\ShoppingCart\Persistence\ShoppingCartRepository;
use App\Global\Persistence\Repository\ItemRepository;
use App\Global\Persistence\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class OrderFlowController extends AbstractController
{
    public function __construct(public ?Array $response = [], public ?Array $total = [])
    {
    }
    #[Route('/order/flow', name: 'app_order_flow')]
    public function index(
        ShoppingCartRepository $cartRepository,
        UserRepository $userRepository,
        SessionInterface $session,
        OrderFlowEntityManager $flowEntityManager,
        Request $request,
        EntityManagerInterface $entityManager,
        OrderFlowValidation $validation
    ): Response {
        $email = $session->get('user');
        $user = $userRepository->findOneByEmail($email);

        // Check if user was found
        if (!$user) {
            // Handle the case where the user is not found. Possibly redirect or throw an exception.
            throw $this->createNotFoundException('User not found.');
        }

        $userID = $user->getId();
        $itemsInCart = $cartRepository->findByUserId($userID);
        $total = $cartRepository->getTotal($userID);

        $response = null;
        if ($request->isMethod('POST')) {
            $response = $flowEntityManager->persistOrder($request, $entityManager, $validation, $cartRepository, $email);

            if ($response === null) {
                return $this->redirectToRoute('app_order_flow_thankyou');
            }
        }

        return $this->render('order_flow/index.html.twig', [
            'email' => $email,
            'items' => $itemsInCart,
            'costs' => $total,
            'response' => $response
        ]);
    }
    #[Route('/order/flow/thankyou', name: 'app_order_flow_thankyou')]
    public function success(OrdersRepository $repository, SessionInterface $session, ItemRepository $itemRepository): Response
    {
        $user = $session->get('user');
        $mostRecentOrder = $repository->findMostRecentOrderByEmail($user);
        $items = $itemRepository->findItemsByArrayOfIds($mostRecentOrder->getItems());

        return $this->render('thank_you/index.html.twig',
        [
            'items' => $items,
            'order' => $mostRecentOrder,
        ]);
    }
}
