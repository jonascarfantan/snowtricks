<?php

namespace App\Auth\Action;

use App\Auth\Domain\Form\RegistrationType;
use App\Auth\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class Login extends AbstractController {
    #[Route(path: '/login', name: 'login', methods: ['GET', 'POST'])]
    public function __invoke(Request $request, UserRepository $user_repository): Response {
        dd($user_repository->findAll());
        $form = $this->createForm(RegistrationType::class);
        
        return $this->render('security/login.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
