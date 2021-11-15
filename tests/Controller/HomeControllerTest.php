<?php

namespace App\Tests\Controller;

use App\Entity\Trick;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class HomeControllerTest extends WebTestCase
{
    public function test(): void
    {
        $client = static::createClient();
        $entityManager = $client->getContainer()->get('doctrine.orm.default_entity_manager');
        $nbTricks = count($entityManager->getRepository(Trick::class)->findAll());

        $crawler = $client->request('GET', '/');

        $this->assertResponseIsSuccessful();
        $this->assertCount($nbTricks > 15 ? 15 : $nbTricks, $crawler->filter('.trickNameLink'));
    }
}
