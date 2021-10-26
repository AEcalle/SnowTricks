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

        for ($i = 0; $i < 10; ++$i) {
            $trick = new Trick();
            $slugger = new AsciiSlugger();

            $trick->setName($faker->unique->sentence(2, false));
            $trick->setSlug($slugger->slug($trick->getName())->toString());
            $trick->setDescription($faker->paragraphs(mt_rand(2, 5), true));
            $trick->setCategory($this->getReference(Category::class.'_'.$faker->numberBetween(0, 2)));
            $trick->setUser($this->getReference(User::class.'_'.$faker->numberBetween(0, 4)));

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
