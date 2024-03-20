<?php

namespace App\Components\Homepage\Communication;

use App\Components\Homepage\Business\HomepageBusinessFacadeInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
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

    #[Route('/home/browse/{slug}', name: 'app_browse')]
    public function browse(string $slug): Response
    {
        $response = $this->facade->itemTransfer($slug);
        return new JsonResponse(
            $response
        );
    }
}
