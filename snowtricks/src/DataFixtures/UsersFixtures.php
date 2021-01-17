<?php

namespace App\DataFixtures;

use App\Auth\Domain\Entity\Role;
use App\Auth\Domain\Entity\User;
use App\Auth\Domain\Repository\RoleRepository;
use Carbon\Carbon;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use JetBrains\PhpStorm\NoReturn;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UsersFixtures extends Fixture
{
    public const USER_REFERENCE = 'user';
    
    #[NoReturn] public function __construct(
        private UserPasswordEncoderInterface $encoder,
        private RoleRepository $role_repo,
    ) { }
    
    public function load(ObjectManager $manager)
    {
        $carbon = new Carbon();
        $faker = Factory::create('fr_FR');
        $now = $carbon->now();
        
        $role_admin = new Role();
        $role_admin->setSlug('ROLE_ADMIN');
        $role_admin->setTitle('administrateur');
        $manager->persist($role_admin);
        $role_user = new Role();
        $role_user->setSlug('ROLE_USER');
        $role_user->setTitle('utilisateur');
        $manager->persist($role_user);
        $manager->flush();
        
        $user = new User();
        $user->setUsername("admin");
        $user->setEmail("admin@snowtricks.com");
        $encoded = $this->encoder->encodePassword($user, 'admin');
        $user->setPassword($encoded);
        $user->setCreatedAt($now);
        $role = $this->role_repo->findOneBy(['slug' => 'ROLE_ADMIN']);
        $user->promote($role);
        $manager->persist($user);
        $this->addReference(self::USER_REFERENCE, $user);
    
        for ($count = 0; $count < 10; $count++) {
            $now = $carbon->now();
            
            $user = new User();
            $user->setUsername($faker->userName);
            $user->setEmail($faker->email);
            $encoded = $this->encoder->encodePassword($user, 'Azerty1234!');
            $user->setPassword($encoded);
            $user->setCreatedAt($now);
            $role = $this->role_repo->findOneBy(['slug' => 'ROLE_USER']);
            $user->promote($role);
            $manager->persist($user);
        }
        $manager->flush();
    }
}
