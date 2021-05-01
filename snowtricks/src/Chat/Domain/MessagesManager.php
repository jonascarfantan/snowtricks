<?php

namespace App\Chat\Domain;

use App\_Core\EntityManager;
use App\Chat\Domain\Entity\Message;
use App\Media\Domain\Entity\Media;
use App\Trick\Domain\Entity\Trick;
use Carbon\Carbon;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\PersistentCollection;
use Symfony\Component\Security\Core\User\UserInterface;

class MessagesManager extends EntityManager {
    private const LIMIT = 10;
    
    public function getMessagesSample(Trick $trick, string $offset): array
    {
        $repo = $this->em->getRepository(Message::class);
        $nb_message = count( $repo->findBy(['trick' => $trick->getId()]) );
        $nb_pages = 0;
        
        if($nb_message !== 0) {
            $nb_pages = ($nb_message % self::LIMIT) === 0 ? (int)floor($nb_message / self::LIMIT) : (int)floor($nb_message / self::LIMIT) + 1;
        }
        
        $segment = $repo->findBy(['trick' => $trick->getId()], ['id' => 'DESC'], self::LIMIT, self::LIMIT * $offset);
        
        $messages = [];
        foreach($segment as $message) {
            
            $messages[] = [
                'id' => $message->getId(),
                'author_name' => $message->getSpeaker()->getUsername(),
                'content' => $message->getContent(),
                'avatar' => $message->getSpeaker()->getAvatar()->getPath(),
                'date' => $message->getCreatedAt()->format('Y-m-d'),
                ];
        }
        return [ 'messages' => $messages, 'nb_pages' => $nb_pages ];
    }
    
}
