<?php

namespace App\Home\Action;

use App\Trick\Domain\Entity\Trick;
use App\Trick\Domain\TricksManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class HomePage extends AbstractController {
    
    #[Route(path: '/', name: 'home', methods: ['GET'])]
    public function __invoke(Request $request, EntityManagerInterface $em): Response {
        $tricks_manager = new TricksManager([Trick::class], $em);
        $tricks = $tricks_manager->getAllTricks();
        return $this->render('public/index.html.twig', [
            'title' => 'Snowtricks - Communoté de Free Rider',
            'tricks' => $tricks,
            'action_name' => 'HomePage',
        ]);
    }
}
