<?php

namespace App\Auth\Action;

use App\_Core\Action\ActionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class Login extends AbstractController implements ActionInterface {
    #[NoReturn]
    #[Route(path: '/login', name: 'login')]
    public function __invoke(): Response {
        dd('hi');
    }
}
