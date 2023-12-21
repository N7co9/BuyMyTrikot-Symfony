<?php

namespace App\Components\Orderflow\Communication;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Components\Orderflow\Business\Validation\OrderFlowValidation;
use App\Components\Orderflow\Persistence\OrderFlowEntityManager;
use App\Components\Orderflow\Persistence\OrdersRepository;
use App\Components\ShoppingCart\Persistence\ShoppingCartEntityManager;
use App\Components\ShoppingCart\Persistence\ShoppingCartRepository;
use App\Global\Persistence\Repository\ItemRepository;
use App\Global\Persistence\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

class OrderFlowController extends AbstractController
{
    public function __construct(public ?Array $response = [], public ?Array $total = [])
    {
    }

    #[Route('/order/flow', name: 'app_order_flow')]
    public function index(
        ShoppingCartRepository $cartRepository,
        UserRepository $userRepository,
        Security $security,
        OrderFlowEntityManager $flowEntityManager,
        Request $request,
        EntityManagerInterface $entityManager,
        OrderFlowValidation $validation,
        ShoppingCartEntityManager $cartEntityManager,
    ): Response {
        $user = $security->getUser();

        if (!$user) {
            throw $this->createNotFoundException('User not authenticated.');
        }

        $email = $user->getUserIdentifier();
        $user = $userRepository->findOneByEmail($email);

        $userID = $user->getId();
        $itemsInCart = $cartRepository->findByUserId($userID);
        $total = $cartRepository->getTotal($userID);

        $response = null;
        if ($request->isMethod('POST')) {
            $response = $flowEntityManager->persistOrder($request, $entityManager, $validation, $cartRepository, $cartEntityManager, $userRepository, $email);

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
    public function success(OrdersRepository $repository, Security $security, ItemRepository $itemRepository): Response
    {
        $user = $security->getUser();

        if (!$user) {
            // Handle the case where the user is not authenticated. Possibly redirect or throw an exception.
            throw $this->createNotFoundException('User not authenticated.');
        }

        $email = $user->getUserIdentifier();
        $mostRecentOrder = $repository->findMostRecentOrderByEmail($email);

        if (!$mostRecentOrder) {
            // Handle the case where no order is found for the user. Possibly redirect or throw an exception.
            throw $this->createNotFoundException('Order not found.');
        }

        $items = $itemRepository->findItemsByArrayOfIds($mostRecentOrder->getItems());

        return $this->render('thank_you/index.html.twig', [
            'items' => $items,
            'order' => $mostRecentOrder,
        ]);
    }
}
