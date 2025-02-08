<?php
namespace App\Tests\Security;

use App\Security\GoogleAuthenticator;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use App\Repository\UserRepository;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class GoogleAuthenticatorTest extends TestCase
{
    private $clientRegistry;
    private $userRepository;
    private $router;

    protected function setUp(): void
    {
        $this->clientRegistry = $this->createMock(ClientRegistry::class);
        $this->userRepository = $this->createMock(UserRepository::class);
        $this->router = $this->createMock(RouterInterface::class);
    }

    public function testSupportsMethod()
    {
        $authenticator = new GoogleAuthenticator(
            $this->clientRegistry,
            $this->userRepository,
            $this->router
        );

        $request = Request::create('/connect/google/check');
        $request->attributes->set('_route', 'connect_google_check');

        $this->assertTrue($authenticator->supports($request));
    }

    public function testAuthenticationSuccess()
    {
        $authenticator = new GoogleAuthenticator(
            $this->clientRegistry,
            $this->userRepository,
            $this->router
        );

        $this->router->method('generate')->willReturn('/');

        $response = $authenticator->onAuthenticationSuccess(
            new Request(),
            $this->createMock(TokenInterface::class),
            'main'
        );

        $this->assertInstanceOf(RedirectResponse::class, $response);
    }
}