<?php

namespace App\Chat\Domain\Entity;

use App\Trick\Domain\Entity\Trick;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Symfony\Component\Security\Core\User\UserInterface;

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
     * @ORM\Column(type="string")
     */
    private string $content;
    
    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private ?\DateTimeInterface $created_at;
    
    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private \DateTime $updated_at;
    
    /**
     * @ORM\ManyToOne(targetEntity="App\Auth\Domain\Entity\User", inversedBy="messages", fetch="EAGER")
     * @JoinColumn(name="contributor_id", referencedColumnName="id")
     */
    protected UserInterface $speaker;
    
    /**
     * @ORM\ManyToOne(targetEntity="App\Trick\Domain\Entity\Trick", inversedBy="messages")
     * @ORM\JoinColumn(name="trick_id", referencedColumnName="id", onDelete="CASCADE" )
     */
    private ?Trick $trick;
    
//    ___________________
//    GETTER AND SETTER
//    ___________________
    
    public function get(string $attribute): mixed
    {
        return $this->$attribute;
    }
    public function set(string $attribute, mixed $value): self
    {
        $this->$attribute = $value;
        
        return $this;
    }
    
    public function setSpeaker(UserInterface $contributor): self
    {
        $this->speaker = $contributor;
        
        return $this;
    }
    
    public function getSpeaker(): UserInterface
    {
        return $this->speaker;
    }
    
    public function getTrick()
    {
        return $this->trick;
    }
    
    public function setTrick(?Trick $trick): self
    {
        $this->trick = $trick;
        $trick->addMessage($this);
        
        return $this;
    }
    
    public function removeTrick(): self
    {
        $this->trick = null;
        
        return $this;
    }
    
    public function getCreatedAt(): \DateTimeInterface {
        return $this->created_at;
    }
    
    public function setCreatedAt(\DateTime $created_at): Message {
        $this->created_at = $created_at;
        
        return $this;
    }
    
    public function getContent(): string {
        return $this->content;
    }
    
    public function setContent(string $content): void {
        $this->content = $content;
    }
    
    public function getId(): int {
        return $this->id;
    }
    
    public function setId(int $id): void {
        $this->id = $id;
    }
    
}
