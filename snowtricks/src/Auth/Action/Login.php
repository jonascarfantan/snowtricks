<?php

namespace App\Auth\Action;

use App\_Core\Action\ActionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class Login extends AbstractController implements ActionInterface {
    
    public function __invoke(): Response {
        // TODO: Implement __invoke() method.
        dd('hi');
    }
}
