<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(): JsonResponse
    {
        return new JsonResponse([
            'status' => 'ok',
            'message' => '🎫 GamesTicket API est en ligne !'
        ]);
    }
}
