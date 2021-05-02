<?php

namespace App\Chat\Action;

use App\Chat\Domain\Entity\Message;
use App\Chat\Domain\MessagesManager;
use App\Trick\Domain\Entity\Trick;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class getMessages extends AbstractController {
    
    #[Route(path: 'tricks/{slug}/offset/{offset}', name: 'feed.messages.json', methods: ['GET', 'HEAD'])]
    public function __invoke(Request $request,
                             Trick $trick,
                             EntityManagerInterface $em,
                             $offset
    ): Response
    {
        $message_manager = new MessagesManager([Message::class], $em);
        $messages = $message_manager->getMessagesSample($trick, $offset);
        return new JsonResponse(['messages' => $messages['messages'], 'nb_pages' => $messages['nb_pages']]);
    }
}
