<?php

namespace App\Controller;

use App\Model\Repository\ItemRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomepageController extends AbstractController
{
    public function __construct(
        private ItemRepository $repository
    )
    {
    }

    #[Route('/home/browse/{slug}', name: 'app_homepage')]
    public function index(string $slug = null): Response
    {
        $items = $this->repository->getItems($slug);

        return $this->render('homepage/index.html.twig', [
            'items' => $items
        ]);
    }
}
