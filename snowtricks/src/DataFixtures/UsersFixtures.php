<?php

namespace App\DataFixtures;

use App\Auth\Domain\Entity\User;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UsersFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        for ($count = 0; $count < 20; $count++) {
            $user = new User();
            $user->setUsername("Titre " . $count);
            $user->setEmail("user$count@mail.com" . $count);
            $user->setUser($this->getReference(TricksFixtures::USER_REFERENCE));
            $manager->persist($user);
        }
        $manager->flush();
    }
    
    public function getDependencies(): array {
        // TODO: Implement getDependencies() method.
        return array(
            TricksFixtures::class,
        );
    }
}
