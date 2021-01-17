<?php

namespace App\Auth\Domain\Entity;

use App\Auth\Domain\Repository\RoleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinTable;
use Doctrine\ORM\Mapping\JoinColumn;
use JetBrains\PhpStorm\Pure;

/**
 * Class Role
 *
 * @package App\Auth\Domain\Entity
 *@ORM\Entity(repositoryClass=RoleRepository::class)
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
    
//    BORING GETTER & SETTER
    public function getTitle(): string { return $this->title; }
    public function getSlug(): string { return $this->slug; }
    public function setTitle(string $title): string { $this->title = $title; return $this; }
    public function setSlug(string $slug): string { $this->slug = $slug; return $this; }
    
}
