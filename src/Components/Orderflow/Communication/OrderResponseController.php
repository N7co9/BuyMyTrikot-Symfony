<?php
declare(strict_types=1);

namespace App\Components\Orderflow\Communication;

use App\Components\Orderflow\Business\OrderFlowBusinessFacade;
use App\Symfony\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderResponseController extends AbstractController
{
    public function __construct(
        private readonly OrderFlowBusinessFacade $orderFlowBusinessFacade,
    )
    {
    }

    #[Route('/order/flow/success', name: 'app_order_flow_success')]
    public function success(Request $request): Response
    {
        $orderDTO = $this->orderFlowBusinessFacade->retrieveOrderFromSession($request);
        $this->orderFlowBusinessFacade->removeOrderFromSession($request);
        $this->orderFlowBusinessFacade->createOrder($orderDTO, $this->getLoggingUser(), $request);

        $mostRecentOrder = $this->orderFlowBusinessFacade->getMostRecentOrder($this->getLoggingUser()->email);

        if (!$mostRecentOrder) {
            throw $this->createNotFoundException('Order not found.');
        }

        $items = $this->orderFlowBusinessFacade->findItemsByArrayOfIds($mostRecentOrder->getItems());

        return $this->render('order_response/success.html.twig', [
            'items' => $items,
            'order' => $mostRecentOrder,
        ]);
    }

    #[Route('/order/flow/abort', name: 'app_order_flow_abort')]
    public function abort(Request $request): Response
    {
        $this->orderFlowBusinessFacade->removeOrderFromSession($request);
        return $this->render('order_response/abort.html.twig');
    }
}