<?php

namespace App;

use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Kernel extends BaseKernel
{
    use MicroKernelTrait;

    public function handle(Request $request, int $type = self::MAIN_REQUEST, bool $catch = true): Response
    {
        // 1. Réponse pour les requêtes OPTIONS (preflight)
        if ($request->isMethod('OPTIONS')) {
            $origin = $request->headers->get('Origin');
            $allowedOrigins = [
                'http://localhost:3002',
                'http://localhost:3004',
                'https://games-ticket-frontend.netlify.app'
            ];

            return new Response(null, 204, [
                'Access-Control-Allow-Origin' => in_array($origin, $allowedOrigins) ? $origin : '*',
                'Access-Control-Allow-Methods' => 'GET, POST, PUT, DELETE, OPTIONS',
                'Access-Control-Allow-Headers' => 'Content-Type, Authorization, X-Requested-With',
                'Access-Control-Allow-Credentials' => 'true',
                'Access-Control-Max-Age' => '3600'
            ]);
        }

        $response = parent::handle($request, $type, $catch);

        // 2. Headers pour les réponses normales
        $origin = $request->headers->get('Origin');
        $response->headers->set('Access-Control-Allow-Origin', $origin ?: '*');
        $response->headers->set('Access-Control-Allow-Credentials', 'true');
        $response->headers->set('Access-Control-Expose-Headers', 'Authorization, Content-Disposition');

        return $response;
    }
}