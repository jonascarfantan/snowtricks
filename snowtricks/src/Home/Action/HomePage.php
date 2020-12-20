<?php

namespace App\Home\Action;

use App\_Core\Action\ActionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class HomePage extends AbstractController implements ActionInterface {

    public function __invoke(): Response {
        return $this->render('base/index.html.twig', [
            'action_name' => 'HomePage',
        ]);
    }
}
