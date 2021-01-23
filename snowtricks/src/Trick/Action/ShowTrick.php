<?php

namespace App\Trick\Action;

use App\Trick\Domain\Entity\Trick;
use App\Trick\Domain\TricksManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class ShowTrick extends AbstractController {
    
    #[Route(path: '/trick/{trick_id}', name: 'show.trick', methods: ['GET'])]
    public function __invoke(Request $request, EntityManagerInterface $em, $trick_id): Response {
        $tricks_manager = new TricksManager([Trick::class], $em);
        $tricks = $tricks_manager->getOneTrick($trick_id);
        return $this->render('public/index.html.twig', [
            'title' => 'Snowtricks - Communoté de Free Rider',
            'action_name' => 'HomePage',
        ]);
    }
}
