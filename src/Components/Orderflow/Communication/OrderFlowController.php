<?php

namespace App\Components\Orderflow\Communication;

use App\Components\Orderflow\Business\OrderFlowBusinessFacade;
use App\Components\Orderflow\Business\OrderFlowBusinessFacadeInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class OrderFlowController extends AbstractController
{
    public function __construct(
        private Security                         $security,
        private OrderFlowBusinessFacadeInterface $facade,
        public ?array                            $response = [],
        public ?array                            $total = []
    )
    {
    }

    #[Route('/order/flow', name: 'app_order_flow')]
    public function index(Request $request
    ): Response
    {
        $user = $this->getUser();
        if (!$user) {
            throw $this->createNotFoundException('User not authenticated.');
        }

        $email = $this->facade->getUserIdentifier($this->security);
        $userID = $this->facade->getUserID($this->security);
        $itemsInCart = $this->facade->getItemsInCart($userID);
        $total = $this->facade->getTotal($userID);


        $response = $this->facade->createOrderFlow($request, $email);
        if ($response === null) {
            return $this->redirectToRoute('app_order_flow_thankyou');
        }

        return $this->render('order_flow/index.html.twig', [
            'email' => $email,
            'items' => $itemsInCart,
            'costs' => $total,
            'response' => $response
        ]);
    }

    #[Route('/order/flow/thankyou', name: 'app_order_flow_thankyou')]
    public function success(): Response
    {
        $user = $this->getUser();

        if (!$user) {
            throw $this->createNotFoundException('User not authenticated.');
        }
        $email = $this->facade->getUserIdentifier($this->security);
        $mostRecentOrder = $this->facade->getMostRecentOrder($email);

        if (!$mostRecentOrder) {
            throw $this->createNotFoundException('Order not found.');
        }
        $items = $this->facade->findItemsByArrayOfIds($mostRecentOrder->getItems());

        return $this->render('thank_you/index.html.twig', [
            'items' => $items,
            'order' => $mostRecentOrder,
        ]);
    }
}
