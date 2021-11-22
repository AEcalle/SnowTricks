<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

final class CategoryFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $names = ['Grab', 'Straight air', 'Spin', 'Flip'];

        for ($i = 0; $i < count($names); ++$i) {
            $category = new Category();

            $category->setName($names[$i]);

            $manager->persist($category);
            $this->addReference(Category::class.'_'.$i, $category);
        }

        $manager->flush();
    }
}
