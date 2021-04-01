<?php

namespace App\Trick\Action;

use App\_Core\Trait\Manager;
use App\Trick\Domain\Entity\Trick;
use App\Trick\Domain\Repository\TrickRepository;
use App\Trick\Domain\TricksManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class Publish extends AbstractController {
    use Manager;
    
    #[Route(path: '/tricks/{id}/publish', name: 'publish.draft.trick', methods: ['GET'])]
    public function __invoke(
        Request $request,
        EntityManagerInterface $em,
        TrickRepository $trick_repository,
        Trick $trick
    ): Response {
        if( ($redirect = $this->redirectUnauthenticated($request)) instanceof Response ) {
            return $redirect;
        }
        $tricks_manager = new TricksManager([Trick::class], $em);
        $current_version = $tricks_manager->getCurrentVersion($trick);
        $current_version->setState('older version');
        $trick->setState('current');
        $em->persist($current_version);
        $em->persist($trick);
        $em->flush();
        
        $trick = $tricks_manager->trickWithTree($trick);
        return $this->render('trick/show.html.twig', [
            'title' => 'Zoom sur ' . $trick['title'],
            'trick' => $trick,
        ]);
    }
}
