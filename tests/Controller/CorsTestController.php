<?php

// src/Controller/CorsTestController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CorsTestController extends AbstractController
{
    #[Route('/test-cors', methods: ['GET', 'OPTIONS'])]
    public function test(): Response
    {
        return new Response('CORS OK!');
    }
}
