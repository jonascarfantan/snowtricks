<?php

namespace App\_Core\Domain;

use App\_Core\Trait\Manager;
use Doctrine\ORM\EntityManagerInterface;

abstract class EntityManager {
    use Manager;
    
    protected array $repos = [];
    
    public function __construct(array $classes, protected EntityManagerInterface $em) {
        foreach($classes as $name => $class) {
            $this->repos[$name] = $this->em->getRepository($class);
        }
    }
    
}
