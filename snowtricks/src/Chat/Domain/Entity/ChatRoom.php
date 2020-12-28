<?php

namespace App\Chat\Domain\Entity;

use Doctrine\ORM\Mapping as ORM;
use phpDocumentor\Reflection\Types\Collection;

/**
 * @package App\Chat\Domain\Entity
 * @ORM\Entity
 */
class ChatRoom
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;
    /**
     * @ORM\ManyToOne(targetEntity="App\Chat\Domain\Entity\Message", inversedBy="chat_room", cascade={"persist", "remove"})
     * @ORM\Column(type="integer", nullable=true)
     */
    protected Collection $messages;
    
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
