<?php

namespace App\Auth\Domain\Entity;

use App\Auth\Domain\Repository\UserRepository;
use App\Trick\Domain\Entity\Trick;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinTable;
use Doctrine\ORM\Mapping\JoinColumn;

use JetBrains\PhpStorm\Pure;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Regex;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
#[
    UniqueEntity(fields: 'username' ,message: 'Pseudo déjà utilisé.'),
    UniqueEntity(fields: 'email' ,message: 'Adresse email déjà utilisé.')
]
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    #[
        Length(min: 4, minMessage: 'Pseudo doit contenir au moins 4 caractères.'),
        NotNull(message: 'Pseudo requis.')
    ]
    private string $username;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    #[
        Email(message: 'Adresse email invalide.'),
        NotNull(message: 'Adresse email requise.')
    ]
    private string $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    #[
        Regex(
            pattern:'/^(?=.*[!@#$%^&*-])(?=.*[0-9])(?=.*[A-Z]).{8,20}$/',
            message: 'Le mot de passe doit contenir 3 type de caractères dont une majuscules un nombres et un spéciale.'),
        NotNull(message: 'Mot de passe requis.')
    ]
    private string $password;
    private string $old_password;
    private string $confirm_password;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private ?\DateTimeInterface $created_at;
    
    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private ?\DateTimeInterface $updated_at;
    
    /**
     * @ORM\ManyToOne(targetEntity="App\Auth\Domain\Entity\Role", cascade={"persist", "remove"}, fetch="EXTRA_LAZY")
     */
    public $role;
    
    /**
     * @ORM\ManyToMany(targetEntity="App\Trick\Domain\Entity\Trick", mappedBy="contributors", cascade={"persist", "remove"})
     * @JoinTable(name="trick_user",
     * joinColumns={@JoinColumn(name="user_id", referencedColumnName="id")},
     * inverseJoinColumns={@JoinColumn(name="trick_id", referencedColumnName="id")}
     * )
     */
    public Collection $tricks;
    private $salt;
    
    #[Pure] public function __construct()
    {
        if(!isset($this->role)){
            $this->roles = ['ROLE_USER'];
        }
        $this->tricks = new ArrayCollection();
    }
    
//    BORING GETTER & SETTER
    public function getId(): string { return $this->id; }
    public function getUsername(): string { return $this->username; }
    public function getEmail(): string { return $this->email; }
    public function getPassword(): string { return $this->password; }
    public function getCreatedAt(): string { return $this->created_at; }
    public function getUpdatedAt(): string { return $this->updated_at; }
    
    public function setUsername(string $username): self { $this->username = $username; return $this; }
    public function setEmail(string $email): self { $this->email = $email; return $this; }
    public function setPassword(string $password): self { $this->password = $password; return $this; }
    public function setCreatedAt(\DateTime $date_time): self { $this->created_at = $date_time; return $this; }
    public function setUpdatedAt(\DateTime $date_time): self { $this->created_at = $date_time; return $this; }
    
    public function getRoles(): array
    {
        return [$this->role->getSlug()];
    }
    
    public function promote(Role $role): self
    {
        $this->role = $role;
        
        return $this;
    }
    
    public function getTricks(): Collection
    {
        return $this->tricks;
    }
    
    public function addTrick(Trick $trick): self
    {
        if(!$this->tricks->contains($trick)) {
            $this->tricks[] = $trick;
            $trick->removeContributor($this);
        }
        
        return $this;
    }
    
    public function removeTrick(Trick $trick): self
    {
        if(!$this->tricks->contains($trick)) {
            $this->tricks->removeElement($trick);
            $trick->removeContributor($this);
        }
        
        return $this;
    }
    
    public function getSalt(): ?string
    {
        return $this->salt;
    }
    
    public function eraseCredentials(): void
    {
    
    }
    
    public function isPasswordValid(UserInterface $user, string $raw) {
        // TODO: Implement isPasswordValid() method.
    }
    
    public function needsRehash(UserInterface $user): bool {
        // TODO: Implement needsRehash() method.
    }
}
