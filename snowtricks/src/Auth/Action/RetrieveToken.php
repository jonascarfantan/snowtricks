<?php

namespace App\Auth\Action;

use App\Auth\Domain\Dto\UpdatePasswordDto;
use App\Auth\Domain\Entity\User;
use App\Auth\Domain\Form\UpdatePasswordType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Security;

final class RetrieveToken extends AbstractController
{
    #[Route('/forgot-password/{id}/{token}', name: 'forgot_password_token', methods: ['GET', 'POST'])]
    public function __invoke(
        Session $session,
        string $token,
        User $user,
        ): Response
    {
        
        $session->set('Reset-Password-Token-URL', $token);
        $session->set('Reset-Password-User-Email', $user->getEmail());
        
        return $this->redirectToRoute('update_password');
    }
    
}
