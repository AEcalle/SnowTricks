<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Trick;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\String\Slugger\AsciiSlugger;

final class TrickFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr FR');

        for ($i = 0; $i < 10; ++$i) {
            $trick = new Trick();
            $slugger = new AsciiSlugger();

            $trick->setName($faker->unique->sentence());
            $trick->setSlug($slugger->slug($trick->getName())->toString());
            $trick->setDescription($faker->paragraph(mt_rand(1, 3)));

            $manager->persist($trick);
            $manager->flush();
        }
    }
}
