<?php

namespace App\Tests\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Uid\Uuid;

class RegistrationControllerTest extends WebTestCase
{
    public function testRegister(): void
    {
        $client = static::createClient();

        $entityManager = $client->getContainer()->get('doctrine.orm.default_entity_manager');

        $nbUsers = count($entityManager->getRepository(User::class)->findAll());

        $crawler = $client->request('GET', 'register');

        $this->assertResponseIsSuccessful();

        $form = $crawler->filter('form[name=registration_form]')->form([
            'registration_form[username]' => 'Bob',
            'registration_form[email]' => 'invalid format',
            'registration_form[plainPassword]' => 'pass', //Too short password
        ]);

        $crawler = $client->submit($form);
        $this->assertTrue($crawler->filter('.invalid-feedback')->count() == 2);

        $form = $crawler->filter('form[name=registration_form]')->form([
            'registration_form[username]' => 'Bob',
            'registration_form[email]' => 'bob@gmail.fr',
            'registration_form[plainPassword]' => 'password',
        ]);

        $client->submit($form);
        $this->assertEmailCount(1);
        $crawler = $client->followRedirect();

        $this->assertEquals($nbUsers+1, count($entityManager->getRepository(User::class)->findAll()));
        $this->assertResponseIsSuccessful();
    }

    public function testValidation()
    {
        $client = static::createClient();

        $entityManager = $client->getContainer()->get('doctrine.orm.default_entity_manager');
        $user = $entityManager->getRepository(User::class)->findOneBy([]);
        $user->setRegistrationToken(Uuid::v4());
        $entityManager->persist($user);
        $entityManager->flush();

        $client->request('GET', 'validation/'.$user->getRegistrationToken());
        $client->followRedirect();

        $this->assertResponseIsSuccessful();
        $this->assertNotNull($user->validedAt); 
    }
}
