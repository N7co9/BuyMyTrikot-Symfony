<?php

namespace App\Controller;

use App\Model\Repository\ItemRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class HomepageController extends AbstractController
{
    public function __construct(
        private ItemRepository $repository,
    )
    {
    }

    #[Route('/home/', name: 'app_homepage')]
    public function index(Request $request): Response
    {
        $session = $request->getSession();
        return $this->render('base.html.twig' ,[
            'user' =>$session->get('user') ?? ''
            ]);
    }

    #[Route('/home/browse/{slug}', name: 'app_browse')]
    public function browse(int $slug, Request $request): Response
    {
        $session = $request->getSession();
        $items = $this->repository->getItems($slug);

        return $this->render('homepage/index.html.twig', [
            'items' => $items,
            'user' => $session->get('user') ?? ''
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
