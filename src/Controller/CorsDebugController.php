<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class CorsDebugController
{
    #[Route('/api/debug/cors', name: 'cors_debug', methods: ['OPTIONS', 'GET', 'POST'])]
    public function debug(Request $request): JsonResponse
    {
        return new JsonResponse([
            'origin' => $request->headers->get('Origin'),
            'access_control_request_method' => $request->headers->get('Access-Control-Request-Method'),
            'method' => $request->getMethod(),
        ]);
    }
}
