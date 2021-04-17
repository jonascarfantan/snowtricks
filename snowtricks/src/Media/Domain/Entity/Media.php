<?php

namespace App\Media\Domain\Entity;

use App\Trick\Domain\Entity\Trick;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Media\Domain\Repository\MediaRepository")
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
     * @ORM\Column(type="boolean", length=64, nullable=true)
     */
    private ?bool $is_banner;
    
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
    private ?string $path = null;
    
    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private ?string $iframe = null;
    
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
     * @ORM\JoinColumn(name="trick_id", referencedColumnName="id", onDelete="CASCADE" )
     */
    private ?Trick $trick;
    
    public function __construct()
    {
        $this->iframe = null;
        $this->path = null;
//        $this->is_banner = null;
    }
    
    public function get(string $attribute): mixed
    {
        return $this->$attribute;
    }
    
    public function set(string $attribute, mixed $value): self
    {
        $this->$attribute = $value;
        
        return $this;
    }
    
    public function getTrick()
    {
        return $this->trick;
    }
    
    public function setTrick(?Trick $trick): self
    {
        $this->trick = $trick;
        $trick->addMedia($this);
        
        return $this;
    }
    
    public function removeTrick(): self
    {
        $this->trick = null;
        
        return $this;
    }
    
    public function setType(string $type): Media {
        $this->type = $type;
        
        return $this;
    }

    public function getType(): string {
        return $this->type;
    }
    
    public function getSlug(): string {
        return $this->slug;
    }
    
    public function setSlug(string $slug): Media {
        $this->slug = $slug;
        
        return $this;
    }

    public function getAlt(): string {
        return $this->alt;
    }
    
    
    public function getPath(): string {
        return $this->path;
    }
    
    
    public function setPath(string $path): Media {
        $this->path = $path;
        
        return $this;
    }

    public function setAlt(string $alt): Media {
        $this->alt = $alt;
        
        return $this;
    }

    public function getIframe(): string {
        return $this->iframe;
    }
    
    public function setIframe(string $iframe): Media {
        $this->iframe = $iframe;
        
        return $this;
    }

    public function getCreatedAt(): \DateTime {
        return $this->created_at;
    }
    
    public function setCreatedAt(\DateTime $created_at): Media {
        $this->created_at = $created_at;
        
        return $this;
    }

    public function getUpdatedAt(): \DateTime {
        return $this->updated_at;
    }
    
    public function setUpdatedAt(\DateTime $updated_at): Media {
        $this->updated_at = $updated_at;
        
        return $this;
    }
    
    public function getId(): int {
        return $this->id;
    }
    
    public function getIsBanner(): bool|null {
        return $this->is_banner;
    }
    
    public function setIsBanner(string $is_banner): Media {
        $this->is_banner = $is_banner;
        
        return $this;
    }
}
