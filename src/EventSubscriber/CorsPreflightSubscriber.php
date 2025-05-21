<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpFoundation\Response;

class CorsPreflightSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        // Augmenter la priorité à 9999 pour s'assurer qu'il s'exécute avant tout autre listener
        return [
            KernelEvents::REQUEST => ['onKernelRequest', 9999],
        ];
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        // Ajouter les headers CORS à toutes les réponses, pas seulement OPTIONS
        if ($event->isMainRequest()) {
            $request = $event->getRequest();

            // Pour les requêtes OPTIONS, on répond immédiatement
            if ($request->getMethod() === 'OPTIONS') {
                $response = new Response();
                $response->headers->set('Access-Control-Allow-Origin', '*');
                $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS');
                $response->headers->set('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-Requested-With');
                $response->headers->set('Access-Control-Max-Age', '3600');
                $response->headers->set('X-Debug-CORS', 'Handled by CorsPreflightSubscriber');
                $event->setResponse($response);
            }
        }
    }
}
