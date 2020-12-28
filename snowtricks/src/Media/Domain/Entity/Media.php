<?php

namespace App\Media\Domain\Entity;

use App\Repository\TrickRepository;
use App\Trick\Domain\Entity\Trick;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TricksRepository::class)
 */
class Media
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;
    /**
     * @ORM\Column(type="string", length=64)
     */
    private string $type;
    /**
     * @ORM\Column(type="string", length=64)
     */
    private string $slug;
    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private string $alt;
    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private string $path;
    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private string $url;
    /**
     * @ORM\Column(type="datetime")
     */
    private \DateTime $created_at;
    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private \DateTime $updated_at;
    /**
     * @ORM\ManyToOne(targetEntity="App\Trick\Domain\Entity\Trick", inversedBy="medias")
     * @ORM\JoinColumn(nullable=false, name="trick_id")
     */
    private Trick $trick;
    
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
