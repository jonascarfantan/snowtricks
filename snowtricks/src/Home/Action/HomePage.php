<?php

namespace App\Home\Action;

use App\_Core\Action\ActionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class HomePage extends AbstractController implements ActionInterface {
    
    #[Route(path: '/', name: 'home', methods: ['GET'])]
    public function __invoke(Request $request): Response {
        return $this->render('public/index.html.twig', [
            'title' => 'Snowtricks - Communoté de Free Rider',
            'action_name' => 'HomePage',
        ]);
    }
}
