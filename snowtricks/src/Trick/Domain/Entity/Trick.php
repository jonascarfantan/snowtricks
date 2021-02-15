<?php

namespace App\Trick\Domain\Entity;

use App\Auth\Domain\Entity\User;
use App\Media\Domain\Entity\Media;
use App\Trick\Domain\Repository\TrickRepository;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\JoinTable;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;

use JetBrains\PhpStorm\Pure;
use phpDocumentor\Reflection\Types\Integer;

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
    private int $id;
    
    /**
     * @ORM\Column(type="string", length=32)
     */
    private string $title;
    
    /**
     * @ORM\Column(type="string", length=32)
     */
    private string $slug;
    
    /**
     * @ORM\Column(type="text")
     */
    private string $description;
    
    /**
     * @ORM\Column(type="string", length=32) // [published, draft, deleted]
     */
    private string $state;
    
    /**
     * @ORM\Column(type="integer")
     */
    private int $version;
    
    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private ?\DateTimeInterface $created_at;
    
    /**
     * @ManyToOne(targetEntity="App\Trick\Domain\Entity\Trick", inversedBy="children")
     * @JoinColumn(name="parent", referencedColumnName="id", nullable=true)
     */
    private ?Trick $parent;
    
    /**
     * @OneToMany(targetEntity="App\Trick\Domain\Entity\Trick", mappedBy="parent", fetch="EAGER")
     */
    private Collection|Trick $children;
    
    /**
     * @ManyToOne(targetEntity=User::class, inversedBy="tricks", fetch="EAGER")
     * @JoinColumn(name="contributor_id", referencedColumnName="id")
     */
    public User $contributor;
    
    /**
     * @OneToMany(targetEntity=Media::class, mappedBy="trick", orphanRemoval=true, cascade={"persist", "remove"}, fetch="EAGER")
     */
    private Collection|Media $medias;
    
    #[Pure] public function __construct()
    {
        $this->medias = new ArrayCollection();
    }
    
    public function setMedias(iterable $medias): self
    {
        $this->clearMedias();
        foreach ($medias as $media) {
            $this->addMedia($media);
        }
        
        return $this;
    }
    
    public function addMedia(Media $media): self
    {
        if ($this->medias->contains($media) === false) {
            $this->medias->add($media);
            $media->setTrick($this);
        }
        
        return $this;
    }
    
    public function getMedias(): iterable
    {
        return $this->medias;
    }
    
    public function removeMedia(Media $media): self
    {
        if ($this->medias->contains($media)) {
            $this->medias->removeElement($media);
            $media->setTrick(null);
        }
        
        return $this;
    }
    
    public function clearMedias(): self
    {
        foreach ($this->getMedias() as $media) {
            $this->removeMedia($media);
        }
        $this->medias->clear();
        
        return $this;
    }
    
    public function setContributor(User $contributor): self
    {
        $this->contributor = $contributor;
        
        return $this;
    }
    
    public function getContributor(): User
    {
        return $this->contributor;
    }
    
    public function addChild(Trick $trick): self
    {
        if ($this->children->contains($trick) === false) {
            $this->children->add($trick);
            $trick->setParent($this);
        }
        
        return $this;
    }
    
    public function getChildren(): iterable
    {
        return $this->children;
    }
    
    public function removeChild(Trick $trick): self
    {
        if ($this->children->contains($trick)) {
            $this->children->removeElement($trick);
            $trick->setParent(null);
        }
        
        return $this;
    }
    
    public function getParent(): self {
        if($this->parent !== null ) {
          return $this->parent;
        } else {
            return $this;
        }
    }
    public function setParent(?Trick $parent): Trick {
        $this->parent = $parent;
        $parent->addChild($this);
        return $this;
    }
    
    public function getId(): int {
        return $this->id;
    }
    public function setId($id): void
    {
        $this->id = $id;
    }
    public function getTitle() {
        return $this->title;
    }
    public function setTitle(string $title): self
    {
        $this->title = $title;
        
        return $this;
    }
    
    public function getSlug() {
        return $this->slug;
    }
    
    public function setSlug($slug): self
    {
        $this->slug = $slug;
        
        return $this;
    }
    
    
    public function getDescription()
    {
        return $this->description;
    }
    
    public function setDescription($description): self
    {
        $this->description = $description;
        
        return $this;
    }

    public function getCreatedAt()
    {
        return $this->created_at;
    }
    
    public function setCreatedAt($created_at): self
    {
        $this->created_at = $created_at;
        
        return $this;
    }
    
    public function getUpdatedAt()
    {
        return $this->updated_at;
    }
    
    public function setUpdatedAt($updated_at): self
    {
        $this->updated_at = $updated_at;
        
        return $this;
    }
    
    public function setState(string $state): self
    {
        $this->state = $state;
        
        return $this;
    }
    
    public function getState() {
        return $this->state;
    }
    
    public function setVersion(int $version): Trick {
        $this->version = $version;
        
        return $this;
    }
    
    public function getVersion(): string {
        return $this->version;
    }
    
}
