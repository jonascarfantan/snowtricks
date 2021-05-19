<?php

namespace App\Auth\Domain\Dto;

use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;

class UpdatePasswordDto
{
    #[UserPassword(message: "Mot de passe incorrect.")]
    private $old_password;
    private $password;
    
    function getOldPassword() {
        return $this->old_password;
    }
    
    function getPassword() {
        return $this->password;
    }
    
    function setOldPassword($old_password) {
        $this->old_password = $old_password;
        return $this;
    }
    
    function setPassword($password) {
        $this->password = $password;
        return $this;
    }
}
