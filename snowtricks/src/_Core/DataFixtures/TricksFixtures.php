<?php

namespace App\_Core\DataFixtures;

use App\_Core\DataFixtures\UsersFixtures;
use App\_Core\Trait\Manager;
use App\Media\Domain\Entity\Media;
use App\Trick\Domain\Entity\Trick;
use Carbon\Carbon;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;

use Faker\Factory;
use JetBrains\PhpStorm\NoReturn;

class TricksFixtures extends Fixture implements DependentFixtureInterface
{
    use Manager;
    
    #[NoReturn] public function __construct(
    ) { }
    
    public function load(ObjectManager $manager)
    {
        $carbon = new Carbon();
        $faker = Factory::create('fr_FR');
        $img_path = [
            '/images/tricks/chill3.webp',
            '/images/tricks/chill1.webp',
            '/images/tricks/chill2.webp',
            '/images/tricks/frontgrab1.webp',
            '/images/tricks/frontgrab2.webp',
            '/images/tricks/backgap1.webp',
            '/images/tricks/backgap2.webp',
            '/images/tricks/contest.webp',
            '/images/tricks/home_page.webp',
            '/images/tricks/mountain1.webp',
            '/images/tricks/slide1.webp',
            '/images/tricks/slide2.webp',
            '/images/tricks/slide3.webp',
        ];
        $categories = [
            'Grab',
            'No foot',
            'One foot',
            'rotation',
            'Back flip',
            'Front flip',
        ];
        
        $trick_name = [
            'Frontside',
            'Backside air',
            'Mc Twist',
            'Crippler',
            'Backside rodeo',
            'Air to fakie',
            'Handplant',
            'Switch Method',
            'Lobster Flip',
            'Nuckle Tricks',
            'Handplant',
            '270',
            'One Foot Tricks',
            'BS540 Seatbelt',
            'FS 720 Japan',
        ];
        $mov_iframe = [
            '<iframe src="https://www.youtube.com/embed/nqNIy8HkEQ8" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>',
            '<iframe src="https://www.youtube.com/embed/q-RAJnV1dfg?start=13" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>',
            '<iframe src="https://www.youtube.com/embed/wlEY-D2F6Yk?start=144" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>',
            '<iframe src="https://www.youtube.com/embed/zeDUIgJ6yjM" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>',
            '<iframe src="https://www.youtube.com/embed/2RlDSbxsnyc?start=92" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>',
            '<iframe src="https://www.youtube.com/embed/BH42KlQ0QsE" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>',
            '<iframe src="https://www.youtube.com/embed/yK5GFfqeYfU" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>',
            '<iframe src="https://www.youtube.com/embed/h0UtyOX9p90" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>',
        ];
        
        $trick_description = [
            'Un grand classique des rotations tête en bas qui se fait en backside, sur un mur backside de pipe. Le Mc Twist est généralement fait en japan, un grab très tweaké (action d\'accentuer un grab en se contorsionnant).',
            'Une autre rotation tête en bas classique qui s\'apparente à un backflip sur un mur frontside de pipe ou un quarter.',
            'Une rotation tête en bas backside tournant dans le sens d\'un backflip qui peut se faire aussi bien sur un kicker, un pipe ou un hip.',
            'En pipe, sur un quarter ou un hip, ce terme désigne un saut sans rotation où le rider retombe dans le sens inverse.',
            'Un trick inspiré du skate qui consiste à tenir en équilibre sur une ou deux mains au sommet d\'une courbe. Existe avec de nombreuses variantes dans les grabs et les rotations.',
            'Le diminutif de corkscrew qui signifie littéralement tire-bouchon et désignait les premières simples rotations têtes en bas en frontside. Désormais, on utilise le mot cork à toute les sauces pour qualifier les figures où le rider passe la tête en bas, peu importe le sens de rotation. Et dorénavant en compétition, on parle souvent de double cork, triple cork et certains riders vont jusqu\'au quadruple cork !',
            'Désigne le degré de rotation, soit 3/4 de tour, fait en entrée ou en sortie sur un jib. Certains riders font également des rotations en 450 degrés avant ou après les jibs.',
            'Un revert n\'est pas une figure à part entière mais c\'est le fait de continuer à tourner sur la neige après une rotation aérienne. Cela montre ainsi que la rotation n\'est pas contrôlée et ça fait perdre des points en compétition.',
        ];
        
        $type = ['img','mov'];
        for ($count = 0; $count < 25; $count++) {
            $now = $carbon->now();
            $trick = new Trick();
            $trick->setTitle($trick_name[rand(0, count($trick_name) - 1)]);
            $trick->setCategory($categories[rand(0, count($categories) - 1)]);
            $trick->setVersion(1);
            $trick->setSlug(Manager::slugable($trick->getTitle(), $trick->getVersion()));
            $trick->setDescription($trick_description[rand(0, count($trick_description) - 1)]);
            $trick->setState('current');
            $trick->setCreatedAt($now);
            $trick->setContributor($this->getReference(UsersFixtures::USER_REFERENCE));
            $manager->persist($trick);
            for ($k = 0; $k < 3; $k++) {
                $now = $carbon->now();
                $media = new Media();
                $media->setSlug($faker->word);
                if('mov' === $type[rand(0, 1)]) {
                    $media->setIframe($mov_iframe[rand(0, count($mov_iframe) - 1)]);
                    $media->setType('mov');
                } else {
                    $media->setPath($img_path[rand(0, count($img_path) - 1)]);
                    $media->setAlt($faker->word);
                    $media->setType('img');
                }
                $media->setTrick($trick);
                $media->setCreatedAt($now);
                $manager->persist($media);
            }
            // One banner img at minimum
            $media = new Media();
            $media->setSlug($faker->word);
            $media->setPath($img_path[rand(0, count($img_path) - 1)]);
            $media->setAlt($faker->word);
            $media->setType('img');
            $media->setIsBanner(true);
            $media->setCreatedAt($now);
            $media->setTrick($trick);
            $manager->persist($media);
        }
        $manager->flush();
    }
    
    public function getDependencies(): array {
        return array(
            UsersFixtures::class,
        );
    }

}
