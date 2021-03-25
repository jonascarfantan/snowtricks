<?php

namespace App\Trick\Action;

use App\Trick\Domain\Entity\Trick;
use App\Trick\Domain\Repository\TrickRepository;
use App\Trick\Domain\TricksManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class Show extends AbstractController {
    
    #[Route(path: '/tricks/{id}', name: 'show.trick', methods: ['GET'])]
    public function __invoke(
        Request $request,
        EntityManagerInterface $em,
        TrickRepository $trick_repository,
        $id
    ): Response {
        
        $tricks_manager = new TricksManager([Trick::class], $em);
        $trick = $tricks_manager->trickWithTree($trick_repository->find($id));
        
        return $this->render('trick/show.html.twig', [
            'title' => 'Zoom sur ' . $trick['title'],
            'trick' => $trick,
        ]);
    }
}
