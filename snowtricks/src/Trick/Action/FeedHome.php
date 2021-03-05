<?php

namespace App\Trick\Action;

use App\Trick\Domain\Entity\Trick;
use App\Trick\Domain\TricksManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class FeedHome extends AbstractController {
    
    #[Route(path: '/feed/{offset}/tricks', name: 'feed.tricks.json', methods: ['GET', 'HEAD'])]
    public function __invoke(Request $request, EntityManagerInterface $em, $offset): Response {
        $tricks_manager = new TricksManager([Trick::class], $em);
        $tricks = $tricks_manager->getHomeSample($offset);
        return new JsonResponse(['tricks' => $tricks]);
    }
}
