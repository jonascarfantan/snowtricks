<?php

namespace App\Chat\Domain\Entity;

use App\Repository\PublicMessageRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PublicMessageRepository::class)
 */
class Message
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;
    /**
     * @ORM\ManyToOne(targetEntity="App\Auth\Domain\Entity\User", inversedBy="user", cascade={"persist", "remove"})
     */
    protected int $author;
    
    /**
     * @ORM\Column(type="string")
     */
    private string $content;
    
    public function get(string $attribute): mixed
    {
        return $this->$attribute;
    }
    public function set(string $attribute, mixed $value): self
    {
        $this->$attribute = $value;
        
        return $this;
    }
}
