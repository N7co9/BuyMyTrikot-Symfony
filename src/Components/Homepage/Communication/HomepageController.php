<?php

namespace App\Components\Homepage\Communication;

use App\Components\Homepage\Business\HomepageBusinessFacadeInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomepageController extends AbstractController
{
    public function __construct(
        private readonly HomepageBusinessFacadeInterface $facade,
    )
    {
    }

    #[Route('/home/', name: 'app_homepage')]
    public function index(): Response
    {
        dd('YOU NEED TO UNFUCK THE BILLINGVALIDATION. YOU NEED SEPERATE VALIDATION METHODS SINCE YOU FUCKED THE NAMING UP BETWEEN ORDER AND BILLINGDTO.');
        return $this->render('base.html.twig', [
            'user' => $this->getUser()
        ]);
    }

    #[Route('/home/browse/{slug}', name: 'app_browse')]
    public function browse(string $slug): Response
    {
        $user = $this->getUser();
        try {
            $items = $this->facade->itemTransfer($slug);
        } catch (Exception $e) {
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
        return $this->redirectToRoute('app_browse', ['slug' => $searchQuery]);
    }

}
