<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

final class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr FR');

        for ($i = 0; $i < 5; ++$i) {
            $user = new User();

            $user->setUsername($faker->userName());
            $user->setEmail($faker->email());
            $user->setPassword($faker->password());
            $user->setPicture($faker->imageUrl(100, 100, 'profile'));
            $user->setIsVerified(true);
            $user->setToken($faker->word());

            $manager->persist($user);
            $this->addReference(User::class.'_'.$i, $user);
        }

        $manager->flush();
    }
}
