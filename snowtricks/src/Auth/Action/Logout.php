<?php

namespace App\Auth\Action;

use App\Auth\Domain\Entity\User;
use App\Auth\Domain\Form\LoginType;
use App\Auth\Domain\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class Logout extends AbstractController {
    #[Route(path: '/logout', name: 'logout', methods: ['GET'])]
    public function __invoke(Request $request, UserRepository $user_repository): Response {
        throw new \LogicException('Something strange just happends, please contact your administrator');
    }
}
