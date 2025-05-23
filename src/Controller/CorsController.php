<?php


namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CorsController extends AbstractController
{
    #[Route('/{any}', name: 'cors_preflight', requirements: ['any' => '.*'], methods: ['OPTIONS'])]
    public function preflight(): Response
    {
        $response = new Response();
        $response->headers->set('Access-Control-Allow-Origin', 'https://games-ticket.netlify.app');
        $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
        $response->headers->set('Access-Control-Allow-Headers', 'Content-Type, Authorization');
        return $response;
    }
}