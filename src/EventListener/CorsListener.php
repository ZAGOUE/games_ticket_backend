<?php

namespace App\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class CorsListener implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::RESPONSE => ['onKernelResponse', 9999],
        ];
    }

    public function onKernelResponse(ResponseEvent $event): void
    {
        $response = $event->getResponse();
        $request = $event->getRequest();

        // Ajouter les en-têtes CORS à toutes les réponses
        $response->headers->set('Access-Control-Allow-Origin', 'https://games-ticket.netlify.app');
        $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
        $response->headers->set('Access-Control-Allow-Headers', 'Content-Type, Authorization');

        // Gérer les requêtes OPTIONS (CORS preflight)
        if ($request->getMethod() === 'OPTIONS') {
            $response->setStatusCode(Response::HTTP_OK);
            $response->setContent('');
            $event->setResponse($response);
        }
    }
}