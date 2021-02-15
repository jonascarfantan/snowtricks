<?php

namespace App\Trick\Action;

use App\Trick\Domain\Entity\Trick;
use App\Trick\Domain\TricksManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class Update extends AbstractController {
    
    #[Route(path: '/trick/update/{id}', name: 'update.trick', methods: ['GET'])]
    public function __invoke(Request $request, EntityManagerInterface $em, $id): Response {
        $tricks_manager = new TricksManager([Trick::class], $em);
        $trick = $tricks_manager->trickWithTree($id);
        
        return $this->render('public/show.html.twig', [
            'title' => 'Zoom sur ' . $trick['title'],
            'trick' => $trick,
        ]);
    }
}
