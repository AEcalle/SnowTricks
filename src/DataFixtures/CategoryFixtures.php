<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

final class CategoryFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr FR');

        for ($i = 0; $i < 3; ++$i) {
            $category = new Category();

            $category->setName($faker->unique->sentence(2, false));

            $manager->persist($category);
            $this->addReference(Category::class.'_'.$i, $category);
        }

        $manager->flush();
    }
}
