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
}
