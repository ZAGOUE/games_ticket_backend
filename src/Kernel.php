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
        $allowedOrigins = [
            'http://localhost:3002',
            'http://localhost:3004',
            'https://games-ticket-frontend.netlify.app'
        ];

        $origin = $request->headers->get('Origin');

        if ($request->isMethod('OPTIONS')) {
            $response = new Response();
            $response->headers->set('Access-Control-Allow-Origin', in_array($origin, $allowedOrigins) ? $origin : 'null');
            $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
            $response->headers->set('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-Requested-With');
            $response->headers->set('Access-Control-Allow-Credentials', 'true');
            return $response;
        }

        $response = parent::handle($request, $type, $catch);

        if (in_array($origin, $allowedOrigins)) {
            $response->headers->set('Access-Control-Allow-Origin', $origin);
            $response->headers->set('Access-Control-Allow-Credentials', 'true');
        }

        return $response;
    }
}