<?php
namespace App\Controller;

use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LoginController extends AbstractController
{
    use OAuthControllerTrait;

    #[Route('/', name: 'app_login')]
    public function login(): Response
    {
        return $this->render('login/index.html.twig', [
            'providers' => ['google', 'facebook', 'apple']
        ]);
    }

    #[Route('/home', name: 'app_home')]
    public function home(): Response
    {
        return $this->render('home/home.html.twig');
    }
}