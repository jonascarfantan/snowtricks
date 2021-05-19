<?php

namespace App\_Core\DataFixtures;

use App\Auth\Domain\Entity\Role;
use App\Auth\Domain\Entity\User;
use App\Auth\Domain\Repository\RoleRepository;
use App\Media\Domain\Entity\Media;
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
        
        // Create roles
        $role_admin = new Role();
        $role_admin->setSlug('ROLE_ADMIN');
        $role_admin->setTitle('administrateur');
        $manager->persist($role_admin);
        
        $role_user = new Role();
        $role_user->setSlug('ROLE_USER');
        $role_user->setTitle('utilisateur');
        $manager->persist($role_user);
        $manager->flush();
        
        // Create an admin
        $user = new User();
        $user->setUsername("admin");
        $user->setEmail("admin@snowtricks.com");
        $encoded = $this->encoder->encodePassword($user, 'admin');
        $user->setPassword($encoded);
        $user->setCreatedAt($now);
        $user->setAvatar($this->createAvatar($user->getUsername(), $now, $faker));
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
            $avatar = $this->createAvatar($user->getUsername(), $now, $faker);
            
            
            $user->setAvatar($avatar);
            $manager->persist($user);
        }
        $manager->flush();
    }
    
    private function createAvatar(string $username, \DateTimeInterface $now, $faker): Media
    {
        $avatar_path = [
            '/images/fake/181989_fake.webp',
            '/images/fake/898424_fake.webp',
            '/images/fake/1087536_fake.webp',
            '/images/fake/1394524_fake.webp',
            '/images/fake/1564832_fake.webp',
            '/images/fake/2102412_fake.webp',
            '/images/fake/3550386_fake.webp',
            '/images/fake/3765240_fake.webp',
            '/images/fake/3944519_fake.webp',
            '/images/fake/4402086_fake.webp',
            '/images/fake/6092843_fake.webp',
            '/images/fake/6957380_fake.webp',
        ];
        
        $media = new Media();
        $media->setSlug($faker->word);
        $media->setType('avatar');
        $media->setPath($avatar_path[rand(0, count($avatar_path) - 1)]);
        $media->setAlt('Avatar du membre '.$username);
        $media->setCreatedAt($now);
        
        return $media;
    }
    
}
