<?php

namespace App\Auth\Domain\Entity;

use App\Auth\Domain\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;

use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
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
#[UniqueEntity(fields: 'username' ,message: 'Pseudo déjà utilisé.')]
#[UniqueEntity(fields: 'email' ,message: 'Adresse email déjà utilisé.')]
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
    #[Length(min: 4, minMessage: 'Pseudo doit contenir au moins 4 caractères.')]
    #[NotNull(message: 'Pseudo requis.')]
    private string $username;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    #[Email(message: 'Adresse email invalide.')]
    #[NotNull(message: 'Adresse email requise.')]
    private string $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    #[NotNull(message: 'Mot de passe requis.')]
    #[Regex(pattern:'/^(?=.*[!@#$%^&*-])(?=.*[0-9])(?=.*[A-Z]).{8,20}$/' , message: 'Le mot de passe doit contenir 3 type de caractères dont une majuscules un nombres et un spéciale.')]
    private string $password;
    #[UserPassword(message: 'Mot de passe incorect.')]
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
     * @ORM\ManyToOne(targetEntity="App\Auth\Domain\Entity\Role", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="role_id", referencedColumnName="id", onDelete="cascade", nullable=false)
     */
    private Role $role;
    private $salt;
//    BORING GETTER & SETTER
    public function getUsername(): string { return $this->username; }
    public function setUsername(string $username): self { $this->username = $username; return $this; }
    public function getEmail(): string { return $this->email; }
    public function setEmail(string $email): self { $this->email = $email; return $this; }
    public function getPassword(): string { return $this->password; }
    public function setPassword(string $password): self { $this->password = $password; return $this; }
    public function getRole(): Role { return $this->role; }
    public function addRole(Role $role): self { $this->role = $role; return $this; }
    public function getSalt(): ?string { return $this->salt; }
    
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
    public function get(string $attribute): mixed
    {
        return $this->$attribute;
    }
    public function set(string $attribute, mixed $value): self
    {
        $this->$attribute = $value;

        return $this;
    }
    public function getRoles(): array
    {
        $roles = (array)$this->role->getSlug();
        $roles[] = 'ROLE_USER';
        return array_unique($roles);
    } //Symfony intrusif stuff
    
    public function eraseCredentials(): void
    {
    
    }
    
    public function encodePassword(UserInterface $user, string $plainPassword) {
        // TODO: Implement encodePassword() method.
    }
    
    public function isPasswordValid(UserInterface $user, string $raw) {
        // TODO: Implement isPasswordValid() method.
    }
    
    public function needsRehash(UserInterface $user): bool {
        // TODO: Implement needsRehash() method.
    }
}
