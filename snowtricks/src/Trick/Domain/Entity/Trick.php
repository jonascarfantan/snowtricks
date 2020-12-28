<?php

namespace App\Trick\Domain\Entity;

use App\Repository\TrickRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TrickRepository::class)
 */
class Trick
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;
    /**
     * @ORM\Column(type="string", length=64)
     */
    private $title;
    /**
     * @ORM\Column(type="string", length=64)
     */
    private $slug;
    /**
     * @ORM\Column(type="text")
     */
    private $description;
    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;
    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updated_at;
    /**
     * @ORM\OneToMany(targetEntity="App\Media\Domain\Entity\Media", mappedBy="trick", orphanRemoval=true, cascade={"persist", "remove"})
     */
    private $medias;
    /**
     * @ORM\OneToOne(targetEntity="App\Chat\Domain\Entity\ChatRoom", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="chat_room_id", referencedColumnName="id", nullable=true)
     */
    private $chat_room;
    
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
