<?php

namespace App\Tests\Controller;

use App\Entity\Trick;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TrickControllerTest extends WebTestCase
{
    public function testShow(): void
    {
        $client = static::createClient();

        $entityManager = $client->getContainer()->get('doctrine.orm.default_entity_manager');
        $testUser = $entityManager->getRepository(User::class)->findOneBy([]);
        $client->loginUser($testUser);

        $trick = $entityManager->getRepository(Trick::class)->findOneBy([]);
        $nbComments = count($trick->getComments());

        $crawler = $client->request('GET', 'trick/'.$trick->getSlug());

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', $trick->getName());
        $this->assertCount($nbComments > 5 ? 5 : $nbComments, $crawler->filter('.comment-content'));


        $form = $crawler->filter('form[name=comment]')->form([
            'comment[content]' => '...',
        ]);
        $client->submit($form);
        $crawler = $client->followRedirect();

        $this->assertCount(($nbComments + 1) > 5 ? 5 : ($nbComments + 1), $crawler->filter('.comment-content'));
    }
}
