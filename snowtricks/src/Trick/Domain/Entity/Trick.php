<?php

namespace App\Trick\Domain\Entity;

use App\Auth\Domain\Entity\User;
use App\Media\Domain\Entity\Media;
use App\Chat\Domain\Entity\Message;

use App\Trick\Domain\Repository\TrickRepository;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;

use JetBrains\PhpStorm\Pure;
use Symfony\Component\Security\Core\User\UserInterface;

//#[
//    UniqueEntity(fields: 'slug' ,message: 'Le slug doit être unique.'),
//]
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
    private ?int $id;
    
    /**
     * @ORM\Column(type="string", length=32)
     */
    protected string $title;
    
    /**
     * @ORM\Column(type="string", length=32, nullable=true)
     */
    protected ?string $slug = null;
    
    /**
     * @ORM\Column(type="string", length=32)
     */
    protected string $category;
    
    /**
     * @ORM\Column(type="text")
     */
    protected string $description;
    
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
    public UserInterface $contributor;
    
    /**
     * @OneToMany(targetEntity=Media::class, mappedBy="trick", orphanRemoval=true, cascade={"persist", "remove"}, fetch="EAGER")
     */
    private Collection|Media $medias;
    
    /**
     * @OneToMany(targetEntity=Message::class, mappedBy="trick", orphanRemoval=true, cascade={"persist", "remove"}, fetch="EAGER")
     */
    private Collection|Message $messages;
    
    //    ___________________
    //    GETTER AND SETTER
    //    ___________________
    
    #[Pure] public function __construct()
    {
        $this->medias = new ArrayCollection();
    }

    public function set(string $attribute, string $value) {
        $this->$attribute = $value;
    }
    
    public function get(string $attribute) {
        return $this->$attribute;
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
            $media->removeTrick();
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
    
    
    public function setMessages(iterable $messages): self
    {
        $this->clearMessages();
        foreach ($messages as $message) {
            $this->addMessage($message);
        }
        
        return $this;
    }
    
    public function addMessage(Message $message): self
    {
        if ($this->messages->contains($message) === false) {
            $this->messages->add($message);
            $message->setTrick($this);
        }
        return $this;
    }
    
    public function getMessages(): iterable
    {
        return $this->messages;
    }
    
    public function removeMessage(Message $message): self
    {
        if ($this->messages->contains($message)) {
            $this->messages->removeElement($message);
            $message->removeTrick();
        }
        
        return $this;
    }
    
    public function clearMessages(): self
    {
        foreach ($this->getMedias() as $message) {
            $this->removeMedia($message);
        }
        $this->messages->clear();
        
        return $this;
    }
    
    
    
    public function setContributor(UserInterface $contributor): self
    {
        $this->contributor = $contributor;
        
        return $this;
    }
    
    public function getContributor(): UserInterface
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
    
    public function setChildren(Collection|Trick $children): Trick {
        $this->children = $children;
        
        return $this;
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
    
    public function setSlug(?string $slug = null): self
    {
        if(is_null($slug)) {
            $slug = str_replace(' ','-',strtolower($this->title)).'-v-'.(string)$this->version;
        }
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
    
    public function getCategory(): string {
        return $this->category;
    }
    
    public function setCategory(string $category): self {
        $this->category = $category;
        
        return $this;
    }
    
    
}
