<?php

namespace App\DataFixtures;

use App\DataFixtures\UsersFixtures;
use App\Trick\Domain\Entity\Trick;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TricksFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        for ($count = 0; $count < 20; $count++) {
            $trick = new Trick();
            $trick->setTitle("Titre " . $count);
            $trick->setSlug("titre" . $count);
            $trick->setDescription("lorem ipsum" . $count);
            $trick->addContributors($this->getReference(UsersFixtures::USER_REFERENCE));
            $manager->persist($trick);
        }
        $manager->flush();
    }
    
    public function getDependencies(): array {
        return array(
            UsersFixtures::class,
        );
    }
}
