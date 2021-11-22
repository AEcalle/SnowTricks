<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class UserFixtures extends Fixture
{
    private $userPasswordHasherInterface;

    public function __construct(UserPasswordHasherInterface $userPasswordHasherInterface)
    {
        $this->userPasswordHasherInterface = $userPasswordHasherInterface;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr FR');

        for ($i = 0; $i < 5; ++$i) {
            $user = new User(new \DateTimeImmutable());

            $user->setUsername($faker->userName());
            $user->setEmail($faker->email());
            $user->setPassword(
                $this->userPasswordHasherInterface->hashPassword(
                        $user,
                        $faker->password()
                )
                );
            $user->setPicture($faker->imageUrl(100, 100, 'profile'));
            $user->setRegistrationToken(null);
            $user->setNewPasswordToken(null);

            $manager->persist($user);
            $this->addReference(User::class.'_'.$i, $user);
        }

        $manager->flush();
    }
}
