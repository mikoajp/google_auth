<?php
namespace App\Security;

use App\Repository\UserRepository;
use League\OAuth2\Client\Provider\ResourceOwnerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use KnpU\OAuth2ClientBundle\Security\Authenticator\OAuth2Authenticator;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;

abstract class AbstractOAuthAuthenticator extends OAuth2Authenticator
{

    public function __construct(
        protected ClientRegistry $clientRegistry,
        protected UserRepository $userRepository,
        protected RouterInterface $router
    ) {}

    abstract protected function getProviderName(): string;
    abstract protected function getOAuthUserData(ResourceOwnerInterface $owner): array;

    public function onAuthenticationSuccess(
        Request $request,
        TokenInterface $token,
        string $firewallName
    ): RedirectResponse
    {
        return new RedirectResponse($this->router->generate('app_home'));
    }

    public function onAuthenticationFailure(
        Request $request,
        AuthenticationException $exception
    ): RedirectResponse
    {
        return new RedirectResponse(
            $this->router->generate('app_login', ['error' => $exception->getMessage()])
        );
    }
    public function supports(Request $request): ?bool
    {
        return $request->attributes->get('_route') === 'connect_' . $this->getProviderName() . '_check';
    }

    public function authenticate(Request $request): SelfValidatingPassport
    {
        $client = $this->clientRegistry->getClient($this->getProviderName());
        $accessToken = $this->fetchAccessToken($client);

        return new SelfValidatingPassport(
            new UserBadge($accessToken->getToken(), function() use ($accessToken, $client) {
                $owner = $client->fetchUserFromToken($accessToken);
                return $this->userRepository->findOrCreateFromOauth(
                    $this->getOAuthUserData($owner),
                    $this->getProviderName()
                );
            })
        );
    }


}