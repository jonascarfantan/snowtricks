<?php

namespace App\Trick\Domain;

use App\_Core\Domain\EntityManager;
use App\Trick\Domain\Entity\Trick;

class TricksManager extends EntityManager {
    
    public function getAllTricks()
    {
        $repo = $this->em->getRepository(Trick::class);
        $all = $repo->findBy(['state' => 'published'], null, 10);
        $to_return = [];
        foreach($all as $trick) {
            $to_return[] = $trick;
        }
        return $all;
    }
    
}
