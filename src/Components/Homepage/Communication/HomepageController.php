<?php

namespace App\Components\Homepage\Communication;

use App\Global\Persistence\API\ItemsTransferService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomepageController extends AbstractController
{
    public function __construct(
       private ItemsTransferService $service,
    )
    {
    }

    #[Route('/home/', name: 'app_homepage')]
    public function index(Request $request, Security $security): Response
    {
        $user = $security->getUser();
        return $this->render('base.html.twig' ,[
            'user' => $user
            ]);
    }

    #[Route('/home/browse/{slug}', name: 'app_browse')]
    public function browse(string $slug, Security $security): Response
    {
        $user = $security->getUser();
        try {
            $items = $this->service->itemTransfer($slug);
        }catch (\Exception $e)
        {
            return $this->render('exceptions/404.html.twig');
        }
        return $this->render('homepage/index.html.twig', [
            'items' => $items,
            'user' => $user
        ]);
    }
    #[Route('/home/search', name: 'search_redirect')]
    public function searchRedirect(Request $request): Response
    {
        $searchQuery = $request->query->get('query');

        // Redirect to the browse route with the search query as a slug
        return $this->redirectToRoute('app_browse', ['slug' => $searchQuery]);
    }
}
