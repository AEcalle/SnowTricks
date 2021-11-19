<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Trick;
use App\Entity\Video;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

final class VideoFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr FR');

        for ($i = 0; $i < 30; ++$i) {
            $video = new Video();

            $video->setUrl('https://www.youtube.com/embed/SQyTWk7OxSI');
            $video->setTrick($this->getReference(Trick::class.'_'.$faker->numberBetween(0, 9)));

            $manager->persist($video);
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
