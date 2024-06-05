<?php
declare(strict_types=1);

namespace Proyecto\Infrastructure\Entrypoint\MVC\Security;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    //ruta para el login
    #[Route('/', name: 'login-view')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('Security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $authenticationUtils->getLastAuthenticationError(),
        ]);
    }

    //ruta para desloguearse
    #[Route('/logout', name: 'logout-view')]
    public function logout(): Response
    {
        throw new \Exception('logout()');
    }
}