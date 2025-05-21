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
        // Liste des origines autorisées
        $allowedOrigins = [
            'https://games-ticket.netlify.app',
            'http://localhost:3000',
            'http://localhost:3002',
            'http://localhost:3004',
            'https://games-ticket-frontend.netlify.app'
        ];

        $origin = $request->headers->get('Origin');

        // Gestion spécifique des requêtes OPTIONS (prévol)
        if ($request->isMethod('OPTIONS')) {
            $response = new Response(null, 204);
            if (in_array($origin, $allowedOrigins)) {
                $response->headers->set('Access-Control-Allow-Origin', $origin);
                $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
                $response->headers->set('Access-Control-Allow-Headers', 'Content-Type, Authorization');
                $response->headers->set('Access-Control-Max-Age', '3600');
            }
            return $response;
        }

        // Traitement normal de la requête
        $response = parent::handle($request, $type, $catch);

        // Ajout des headers CORS pour les réponses normales
        if (in_array($origin, $allowedOrigins)) {
            $response->headers->set('Access-Control-Allow-Origin', $origin);
            $response->headers->set('Access-Control-Allow-Credentials', 'true');
            $response->headers->set('Vary', 'Origin');
        }

        return $response;
    }
}