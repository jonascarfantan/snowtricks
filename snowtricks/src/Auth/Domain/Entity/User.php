<?php

namespace App\Auth\Domain\Entity;

use App\Auth\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $pseudo;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true, nullable=true)
     */
    private ?string $lastname;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $password;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private ?\DateTimeInterface $created_at;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private ?\DateTimeInterface $updated_at;

    /**
     * @ORM\OneToOne(targetEntity="App\Auth\Domain\Entity\Role", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="role_id", referencedColumnName="id", nullable=false)
     */
    private int $role;
    
    public function __construct(array $user_info)
    {
        foreach($user_info as $attr => $value) {
            $this->$attr = $value;
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
    
    public function getRoles() {
        return $this->role;
    }
    
    public function getSalt() {
        // TODO: Implement getSalt() method.
    }
    
    public function getUsername() {
        // TODO: Implement getUsername() method.
    }
    
    public function eraseCredentials() {
        // TODO: Implement eraseCredentials() method.
    }
    
    public function getPassword() {
        // TODO: Implement getPassword() method.
    }
}
