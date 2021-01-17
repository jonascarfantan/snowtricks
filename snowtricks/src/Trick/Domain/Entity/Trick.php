<?php

namespace App\Trick\Domain\Entity;

use App\Auth\Domain\Entity\User;
use App\Media\Domain\Entity\Media;
use App\Trick\Domain\Repository\TrickRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinTable;
use Doctrine\ORM\Mapping\JoinColumn;

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
     * @ORM\ManyToMany(targetEntity="App\Auth\Domain\Entity\User", inversedBy="tricks", cascade={"persist", "remove"})
     * @JoinTable(name="trick_user",
     * joinColumns={@JoinColumn(name="trick_id", referencedColumnName="id")},
     * inverseJoinColumns={@JoinColumn(name="user_id", referencedColumnName="id")}
     * )
     */
    public Collection $contributors;
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
    private Collection $medias;
    
    public function __construct()
    {
        $this->contributors = new ArrayCollection();
        $this->medias = new ArrayCollection();
    }
    public function getMedias(): Collection
    {
        return $this->medias;
    }
    
    public function addMedia(Media $media): self
    {
        if(!$this->medias->contains($media)) {
            $this->medias[] = $media;
            $media->addTrick($this);
        }
        
        return $this;
    }
    
    public function removeMedia(Media $media): self
    {
        if(!$this->medias->contains($media)) {
            $this->medias->removeElement($media);
            $media->removeTrick($this);
        }
        
        return $this;
    }
    
    public function getContributors(): Collection
    {
        return $this->contributors;
    }
    
    public function addContributor(User $user): self
    {
        if(!$this->contributors->contains($user)) {
            $this->contributors[] = $user;
            $user->addTrick($this);
        }
        
        return $this;
    }
    
    public function removeContributor(User $user): self
    {
        if(!$this->contributors->contains($user)) {
            $this->contributors->removeElement($user);
            $user->removeTrick($this);
        }
        
        return $this;
    }
    
    public function getId() {
        return $this->id;
    }
    public function setId($id): void {
        $this->id = $id;
    }
    public function getTitle() {
        return $this->title;
    }
    public function setTitle(string $title): self {
        $this->title = $title;
        
        return $this;
    }
    
    public function getSlug() {
        return $this->slug;
    }
    public function setSlug($slug): self {
        $this->slug = $slug;
        
        return $this;
    }
    
    
    /**
     * @return mixed
     */
    public function getDescription() {
        return $this->description;
    }
    
    public function setDescription($description): self {
        $this->description = $description;
        
        return $this;
    }

    public function getCreatedAt() {
        return $this->created_at;
    }
    
    public function setCreatedAt($created_at): self {
        $this->created_at = $created_at;
        
        return $this;
    }
    
    public function getUpdatedAt() {
        return $this->updated_at;
    }
    
    public function setUpdatedAt($updated_at): self {
        $this->updated_at = $updated_at;
        
        return $this;
    }
    
}
