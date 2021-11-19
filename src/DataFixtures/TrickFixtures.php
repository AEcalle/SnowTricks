<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Trick;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\String\Slugger\AsciiSlugger;

final class TrickFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr FR');

        $names = ['Back flip', 'Nose Grab', 'Melon', 'Japan air', 'Canadian Bacon', 'Shifty',
        'Ollie', 'Switch ollie', 'Chicken salad', 'Mute', 'Squirrel', 'Cork', 'Invert', ];

        for ($i = 0; $i < count($names); ++$i) {
            $trick = new Trick();
            $slugger = new AsciiSlugger();

            $trick->setName($names[$i]);
            $trick->setSlug($slugger->slug($trick->getName())->toString());
            $trick->setDescription($faker->paragraphs(mt_rand(2, 5), true));
            $trick->setCategory($this->getReference(Category::class.'_'.mt_rand(0, 2)));
            $trick->setUser($this->getReference(User::class.'_'.mt_rand(0, 4)));

            $manager->persist($trick);
            $this->addReference(Trick::class.'_'.$i, $trick);
        }
        $manager->flush();
    }

    /**
     * @return array<int, Object>
     */
    public function getDependencies(): array
    {
        return [
            CategoryFixtures::class,
            UserFixtures::class,
        ];
    }
}
