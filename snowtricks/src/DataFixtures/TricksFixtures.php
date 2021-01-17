<?php

namespace App\DataFixtures;

use App\DataFixtures\UsersFixtures;
use App\Media\Domain\Entity\Media;
use App\Trick\Domain\Entity\Trick;
use Carbon\Carbon;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

use Faker\Factory;
use JetBrains\PhpStorm\NoReturn;

class TricksFixtures extends Fixture implements DependentFixtureInterface
{
    #[NoReturn] public function __construct(
    ) { }
    
    public function load(ObjectManager $manager)
    {
        $carbon = new Carbon();
        $faker = Factory::create('fr_FR');
    
        for ($count = 0; $count < 50; $count++) {
            $now = $carbon->now();
            $trick = new Trick();
            $trick->setTitle($faker->word());
            $trick->setSlug($faker->word());
            $trick->setDescription($faker->word());
            $trick->setState('published');
            $trick->setCreatedAt($now);
            $trick->addContributor($this->getReference(UsersFixtures::USER_REFERENCE));
            $manager->persist($trick);
            for ($k = 0; $k < 3; $k++) {
                $now = $carbon->now();
                $media = new Media();
                $media->setSlug($faker->word);
                $media->setAlt($faker->word);
                $media->setUrl($faker->imageUrl());
                $media->setType('img');
                $media->addTrick($trick);
                $media->setCreatedAt($now);
                $manager->persist($media);
            }
        }
        $manager->flush();
    }
    
    public function getDependencies(): array {
        return array(
            UsersFixtures::class,
        );
    }
}
