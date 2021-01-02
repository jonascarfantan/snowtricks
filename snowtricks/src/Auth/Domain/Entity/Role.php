<?php

namespace App\Auth\Domain\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Role
 *
 * @package App\Auth\Domain\Entity
 * @ORM\Entity
 */
class Role {
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;
    /**
     * @ORM\Column(type="string")
     */
    private string $title;
    /**
     * @ORM\Column(type="string")
     */
    private string $slug;
    
    public function __construct(array $role_info) {
        foreach($role_info as $attr => $value) {
            if (property_exists(self::class, $attr)) {
                $this->$attr = $value;
            }
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
}
