<?php

namespace App\Media\Domain\Entity;

use App\Trick\Domain\Repository\TrickRepository;
use App\Media\Domain\Repository\MediaRepository;
use App\Trick\Domain\Entity\Trick;
use Doctrine\Common\Collections\Collection;
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
    
    public function getTrick()
    {
        return $this->trick;
    }
    
    public function addTrick(Trick $trick): self
    {
        $this->trick = $trick;
        $trick->addMedia($this);
        
        return $this;
    }
    
    /**
     * @param string $type
     *
     * @return Media
     */
    public function setType(string $type): Media {
        $this->type = $type;
        
        return $this;
}
    
    /**
     * @return string
     */
    public function getType(): string {
        return $this->type;
    }
    
    /**
     * @return string
     */
    public function getSlug(): string {
        return $this->slug;
    }
    
    /**
     * @param string $slug
     *
     * @return Media
     */
    public function setSlug(string $slug): Media {
        $this->slug = $slug;
        
        return $this;
}
    
    /**
     * @return string
     */
    public function getAlt(): string {
        return $this->alt;
    }
    
    /**
     * @return string
     */
    public function getPath(): string {
        return $this->path;
    }
    
    /**
     * @param string $path
     *
     * @return Media
     */
    public function setPath(string $path): Media {
        $this->path = $path;
        
        return $this;
}
    
    /**
     * @param string $alt
     *
     * @return Media
     */
    public function setAlt(string $alt): Media {
        $this->alt = $alt;
        
        return $this;
}
    
    /**
     * @return string
     */
    public function getUrl(): string {
        return $this->url;
    }
    
    /**
     * @param string $url
     *
     * @return Media
     */
    public function setUrl(string $url): Media {
        $this->url = $url;
        
        return $this;
}
    
    /**
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime {
        return $this->created_at;
    }
    
    /**
     * @param \DateTime $created_at
     *
     * @return Media
     */
    public function setCreatedAt(\DateTime $created_at): Media {
        $this->created_at = $created_at;
        
        return $this;
}
    
    /**
     * @return \DateTime
     */
    public function getUpdatedAt(): \DateTime {
        return $this->updated_at;
    }
    
    /**
     * @param \DateTime $updated_at
     *
     * @return Media
     */
    public function setUpdatedAt(\DateTime $updated_at): Media {
        $this->updated_at = $updated_at;
        
        return $this;
}
}
