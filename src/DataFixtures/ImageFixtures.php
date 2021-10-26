<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Image;
use App\Entity\Trick;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

final class ImageFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr FR');
        for ($i = 0; $i < 10; ++$i) {
            $image = new Image();

            $image->setFilename($faker->imageUrl(300, 150, 'snowtricks'));
            $image->setTrick($this->getReference(Trick::class.'_'.$faker->numberBetween(0, 9)));

            $manager->persist($image);
        }

        $manager->flush();
    }

    /**
     * @return array<int, Object>
     */
    public function getDependencies(): array
    {
        return [
            TrickFixtures::class,
        ];
    }
}
