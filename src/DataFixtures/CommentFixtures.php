<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Comment;
use App\Entity\Trick;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

final class CommentFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {

        $contents = ['I like this trick', 'Easy one', 'Too diffcult for me', '
        I will try it soon','Nice !', 'The most beautifult trick ever !', 'Hi everyone !',
        'I love Snowboard', 'I love this website', 'Where can i learn SnowTricks ? '];

        for ($i = 0; $i < 100; ++$i) {
            $comment = new Comment();

            $comment->setCreatedAt(new \DateTimeImmutable(date('Y-m-d H:i:s',mt_rand(1634745672,1637424072))));
            $comment->setContent($contents[mt_rand(0,count($contents)-1)]);
            $comment->setUser($this->getReference(User::class.'_'.mt_rand(0, 4)));
            $comment->setTrick($this->getReference(Trick::class.'_'.mt_rand(0, 9)));

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
