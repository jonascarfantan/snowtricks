<?php

namespace App\Auth\Action;

use App\Auth\Domain\Entity\User;
use App\Auth\Domain\Form\LoginType;
use App\Auth\Domain\Repository\RoleRepository;
use App\Auth\Domain\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class Login extends AbstractController {
    
    #[Route(path: '/login', name: 'login', methods: ['GET', 'POST'])]
    public function __invoke(Request $request, UserRepository $user_repository): Response {
        $form = $this->createForm(LoginType::class, new User());
        
        return $this->render('security/login.html.twig', [
            'form' => $form->createView(),
            'title' => 'Connexion',
        ]);
    }
}
