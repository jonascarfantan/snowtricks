<?php

namespace App\Home\Action;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class HomePage extends AbstractController {
    
    #[Route(path: '/', name: 'home', methods: ['GET'])]
    public function __invoke(Request $request, EntityManagerInterface $em): Response {
        return $this->render('public/index.html.twig', [
            'title' => 'Snowtricks - Communoté de Free Rider',
            'action_name' => 'HomePage',
        ]);
    }
}
