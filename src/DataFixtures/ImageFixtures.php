<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Image;
use App\Entity\Trick;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

final class ImageFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $filenames = scandir('public/build/images');

        $length = count($filenames);
        for ($i = 0; $i < $length; ++$i) {
            if (!preg_match('#^[0-9]#', $filenames[$i])) {
                unset($filenames[$i]);
            }
        }
        $filenames = array_values($filenames);

        for ($i = 0; $i < 50; ++$i) {
            $image = new Image();

            $image->setFilename($filenames[mt_rand(0, count($filenames) - 1)]);
            $image->setTrick($this->getReference(Trick::class.'_'.mt_rand(0, 9)));

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
