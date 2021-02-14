<?php

namespace App\Tests\domain;

use App\Auth\Domain\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class AuthTest extends KernelTestCase
{
    private const VALID_EMAIL_CONST_VALUE = 'zoubirlafrippouille@yahoo.fr';
    private const VALID_PSEUDO_CONST_VALUE = 'zoubir';
    private const VALID_PASSWORD_CONST_VALUE = 'Z0ùBy3RL@Frip0ùy3';
    private const INVALID_EMAIL_CONST_VALUE = '@yolanda@grosmail@pom';
    private const INVALID_PSEUDO_CONST_VALUE = 'a';
    private const INVALID_PASSWORD_CONST_VALUE = 'ZapBùD';
    
    private const INVALID_EMAIL_CONST_MESSAGE = 'Adresse email invalide.';
    private const NOTNULL_EMAIL_CONST_MESSAGE = 'Adresse email requise.';
    private const LENGTH_PSEUDO_CONST_MESSAGE = 'Pseudo doit contenir au moins 4 caractères.';
    private const NOTNULL_PSEUDO_CONST_MESSAGE = 'Pseudo requis.';
    private const SECURITY_PASSWORD_CONST_MESSAGE = 'Le mot de passe doit contenir 3 type de caractères dont une majuscules un nombres et un spéciale.';
    private const NOTNULL_PASSWORD_CONST_MESSAGE = 'Mot de passe requis.';
    
    private ValidatorInterface $validator;
    
    protected function setUp(): void
    {
        $kernel = self::bootKernel();
        $validator = $kernel->getContainer()->get('validator');
        $this->validator = $validator;
    }
    
    private function getValidationErrors(User $user, int $numberOfExceptedErrors): ConstraintViolationList
    {
        $errors = $this->validator->validate($user);
        $this->assertCount($numberOfExceptedErrors, $errors);
        
        return $errors;
    }
    
    public function testUserIsValid(): void
    {
        $user = new User();
        $user->setEmail(self::VALID_EMAIL_CONST_VALUE)
             ->setUsername(self::VALID_PSEUDO_CONST_VALUE)
             ->setPassword(self::VALID_PASSWORD_CONST_VALUE);
        
        $this->getValidationErrors($user, 0);
    }
    
    public function testUserIsInvalid(): void
    {
        $user = new User();
        $user->setEmail(self::INVALID_EMAIL_CONST_VALUE)
            ->setUsername(self::INVALID_PSEUDO_CONST_VALUE)
            ->setPassword(self::INVALID_PASSWORD_CONST_VALUE);
        
        $this->getValidationErrors($user, 3);
    }
    
    public function testSomething()
    {
        $this->assertTrue(true);
    }
}
