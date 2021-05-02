<?php

namespace App\_Core\DataFixtures;

use App\Auth\Domain\Entity\User;
use App\Chat\Domain\Entity\Message;
use App\Trick\Domain\Entity\Trick;
use Carbon\Carbon;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use JetBrains\PhpStorm\NoReturn;

class XMessagesFixtures extends Fixture implements DependentFixtureInterface
{
    public const USER_REFERENCE = 'user';
    
    #[NoReturn] public function __construct(
        private EntityManagerInterface $em,
    ) { }
    
    public function load(ObjectManager $manager, )
    {
        $carbon = new Carbon();
        $faker = Factory::create('fr_FR');
    
        $user_repo = $this->em->getRepository(User::class);
        $users = $user_repo->findAll();
    
        $trick_repo = $this->em->getRepository(Trick::class);
        $tricks = $trick_repo->findAll();
        
        foreach($users as $user) {
            $now = $carbon->now();
    
            foreach($tricks as $trick) {
                for($count = 0; $count < 2; $count++) {
                    $message = new Message();
                    $message->setSpeaker($user);
                    $message->setContent($faker->word);
                    $message->setTrick($trick);
                    $message->setCreatedAt($now);
                    $manager->persist($message);
                }
            }
        }
        $manager->flush();
    }
    
    public function getDependencies() {
        return array(
            TricksFixtures::class,
        );
    }
}
