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
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;
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
class User implements UserInterface, UserPasswordEncoderInterface
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
//    #[UserPassword(message: 'Mot de passe incorect.')]
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
     * @ORM\ManyToMany(targetEntity="App\Auth\Domain\Entity\Role", mappedBy="users", cascade={"persist", "remove"}, fetch="EAGER")
     */
    private Collection $roles;
    /**
     * @ORM\ManyToMany(targetEntity="App\Trick\Domain\Entity\Trick", mappedBy="contributors", cascade={"persist", "remove"})
     * @JoinTable(name="user_role",
     * joinColumns={@JoinColumn(name="user_id", referencedColumnName="id")},
     * inverseJoinColumns={@JoinColumn(name="trick_id", referencedColumnName="id")}
     * )
     */
    private Collection $tricks;
    private $salt;
    
    public function __construct(private ?UserPasswordEncoderInterface $encoder = null)
    {
        if(!isset($this->roles)){
            $this->roles = new ArrayCollection();
        }
    }
    
//    BORING GETTER & SETTER
    public function getId(): string { return $this->id; }
    public function getUsername(): string { return $this->username; }
    public function getEmail(): string { return $this->email; }
    public function getPassword(): string { return $this->password; }
    
    public function setUsername(string $username): self { $this->username = $username; return $this; }
    public function setEmail(string $email): self { $this->email = $email; return $this; }
    public function setPassword(string $password): self { $this->password = $password; return $this; }
    
    public function create($user_info): User
    {
        if(null !== $user_info) {
            foreach($user_info as $attr => $value) {
                if (property_exists(self::class, $attr)) {
                    $this->$attr = $value;
                }
            }
            return $this;
        }
    }
    
    public function getRoles()
    {
        return $this->roles;
    }
    
    public function addRole(Role $role): self
    {
        if(!$this->roles->contains($role)) {
            $this->roles[] = $role;
            $role->addUser($this);
        }
    
        return $this;
    }
    
    public function removeRole(Role $role): self
    {
        if(!$this->roles->contains($role)) {
            $this->roles->removeElement($role);
            $role->removeUser($this);
        }
        
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
    
    public function encodePassword(UserInterface $user, string $plainPassword) {
        $this->setPassword($this->encoder->encodePassword($user, $plainPassword));
    }
    
    public function isPasswordValid(UserInterface $user, string $raw) {
        // TODO: Implement isPasswordValid() method.
    }
    
    public function needsRehash(UserInterface $user): bool {
        // TODO: Implement needsRehash() method.
    }
}
