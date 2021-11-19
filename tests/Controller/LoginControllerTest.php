<?php

namespace App\Tests\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class LoginControllerTest extends WebTestCase
{
    public function testLoginSuccess(): void
    {
        $client = static::createClient();

        $crawler = $client->request('GET', 'login');

        $this->assertResponseIsSuccessful();

        $entityManager = $client->getContainer()->get('doctrine.orm.default_entity_manager');
        $userPasswordHasher = $client->getContainer()->get('security.user_password_hasher');

        $testUser = $entityManager->getRepository(User::class)->findOneBy([]);
        $testUser->createdAt = new \DateTimeImmutable();

        $testUser->setPassword(
            $userPasswordHasher->hashPassword(
                    $testUser,
                    'password'
                )
            );
        $entityManager->persist($testUser);
        $entityManager->flush();

        $form = $crawler->filter('form[name=login_form]')->form([
            '_username' => $testUser->getUserName(),
            '_password' => 'password',
        ]);

        $client->submit($form);
        $crawler = $client->followRedirect();

        $this->assertRouteSame('home');
    }

    public function testLoginFailure(): void
    {
        $client = static::createClient();

        $crawler = $client->request('GET', 'login');

        $this->assertResponseIsSuccessful();

        $entityManager = $client->getContainer()->get('doctrine.orm.default_entity_manager');
        $userPasswordHasher = $client->getContainer()->get('security.user_password_hasher');

        $testUser = $entityManager->getRepository(User::class)->findOneBy([]);
        $testUser->createdAt = new \DateTimeImmutable();

        $testUser->setPassword(
            $userPasswordHasher->hashPassword(
                    $testUser,
                    'password'
                )
            );
        $entityManager->persist($testUser);
        $entityManager->flush();

        $form = $crawler->filter('form[name=login_form]')->form([
            '_username' => $testUser->getUserName(),
            '_password' => 'invalidPassword',
        ]);

        $client->submit($form);
        $crawler = $client->followRedirect();

        $this->assertSelectorTextContains('.invalid-feedback','Invalid credentials.');
    }
}
