<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Comment;
use App\Entity\Trick;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

final class CommentFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr FR');

        for ($i = 0; $i < 100; ++$i) {
            $comment = new Comment();

            $comment->setCreatedAt(new \DateTimeImmutable());
            $comment->setContent($faker->paragraph(mt_rand(1, 3)));
            $comment->setUser($this->getReference(User::class.'_'.$faker->numberBetween(0, 4)));
            $comment->setTrick($this->getReference(Trick::class.'_'.$faker->numberBetween(0, 9)));

            $manager->persist($comment);
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
            UserFixtures::class,
        ];
    }
}
