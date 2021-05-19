<?php

namespace App\Auth\Domain\Entity;

use App\Auth\Action\ForgotPassword;
use App\Auth\Domain\Repository\UserRepository;
use App\Chat\Domain\Entity\Message;
use App\Media\Domain\Entity\Media;
use App\Trick\Domain\Entity\Trick;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinTable;
use Doctrine\ORM\Mapping\ManyToMany;

use Doctrine\ORM\Mapping\OneToMany;
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
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $ForgotPasswordToken;
    
    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private ?\DateTimeImmutable $ForgotPasswordTokenRequestedAt;
    
    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private ?\DateTimeImmutable $ForgotPasswordTokenMustBeVerifiedBefore;
    
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
     * @ORM\OneToOne(targetEntity="App\Media\Domain\Entity\Media", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private Media $avatar;
    
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
    private $role;
    
    /**
     * Many Users have Many Groups.
     * @OneToMany(targetEntity="App\Trick\Domain\Entity\Trick", mappedBy="contributor", cascade={"persist", "remove"})
     */
    public Collection|Trick $tricks;
    
    /**
     * @ORM\OneToMany(targetEntity="App\Chat\Domain\Entity\Message", mappedBy="speaker", cascade={"persist", "remove"})
     */
    public Collection|Message $messages;
    
    private $salt;
    
    //    ___________________
    //    GETTER AND SETTER
    //    ___________________
    
    #[Pure] public function __construct()
    {
        if(!isset($this->role)){
            $this->roles = ['ROLE_USER'];
        }
        $this->tricks = new ArrayCollection();
    }
    
    public function getId(): string { return $this->id; }
    public function getUsername(): string { return $this->username; }
    public function getEmail(): string { return $this->email; }
    public function getPassword(): string { return $this->password; }
    public function getCreatedAt(): string { return $this->created_at; }
    public function getUpdatedAt(): string { return $this->updated_at; }
    public function getAvatar(): Media|null { return $this->avatar ?? null; }
    
    public function setUsername(string $username): self { $this->username = $username; return $this; }
    public function setEmail(string $email): self { $this->email = $email; return $this; }
    public function setPassword(string $password): self { $this->password = $password; return $this; }
    public function setCreatedAt(\DateTime $date_time): self { $this->created_at = $date_time; return $this; }
    public function setUpdatedAt(\DateTime $date_time): self { $this->created_at = $date_time; return $this; }
    public function setAvatar(Media $media): self { $this->avatar = $media; return $this; }
    
    
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
    
    public function setTrick(Trick $trick): self
    {
        if(!$this->tricks->contains($trick)) {
            $this->tricks[] = $trick;
        }
        
        return $this;
    }
    
    public function getMessages(): Collection
    {
        return $this->messages;
    }
    
    public function setMessage(Message $message): self
    {
        if(!$this->tricks->contains($message)) {
            $this->messages[] = $message;
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
    
    public function getAvatarPath(): string {
        return $this->avatar_path;
    }
    
    public function setAvatarPath(string $avatar_path): User {
        $this->avatar_path = $avatar_path;
        
        return $this;
}

//    PASSWORD RESET TOKEN
    public function getForgotPasswordToken(): string {
        return $this->ForgotPasswordToken;
    }
    
    public function setForgotPasswordToken(string $ForgotPasswordToken): self {
        $this->ForgotPasswordToken = $ForgotPasswordToken;
        
        return $this;
    }
    
    public function getForgotPasswordTokenRequestedAt(): \DateTimeImmutable {
        return $this->ForgotPasswordTokenRequestedAt;
    }
    
    public function setForgotPasswordTokenRequestedAt(\DateTimeImmutable $ForgotPasswordTokenRequestedAt): self {
        $this->ForgotPasswordTokenRequestedAt = $ForgotPasswordTokenRequestedAt;
        
        return $this;
    }
    
    public function getForgotPasswordTokenMustBeVerifiedBefore(): \DateTimeImmutable {
        return $this->ForgotPasswordTokenMustBeVerifiedBefore;
    }
    
    public function setForgotPasswordTokenMustBeVerifiedBefore(\DateTimeImmutable $ForgotPasswordTokenMustBeVerifiedBefore): self {
        $this->ForgotPasswordTokenMustBeVerifiedBefore = $ForgotPasswordTokenMustBeVerifiedBefore;
        
        return $this;
    }
    
    
}
