<?php
namespace App\Controller;


use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

trait OAuthControllerTrait
{
    #[Route('/connect/{provider}', name: 'connect_provider_start')]
    public function connect(string $provider, ClientRegistry $clientRegistry): RedirectResponse
    {
        return $clientRegistry
            ->getClient($provider)
            ->redirect(['email', 'profile']);
    }

    #[Route('/connect/{provider}/check', name: 'connect_provider_check')]
    public function connectCheck(): Response
    {
        return new Response();
    }
}