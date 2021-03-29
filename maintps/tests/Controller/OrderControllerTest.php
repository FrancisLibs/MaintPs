<?php

namespace tests\Controller;

use App\DataFixtures\AppFixtures;
use App\Tests\Controller\ConnectUserTrait;
use Symfony\Component\HttpFoundation\Response;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class OrderControllerTest extends WebTestCase
{
    use ConnectUserTrait;
    use FixturesTrait;

    public function testOrderIndex()
    {
        $client = static::createClient();
        $this->loadFixtures([AppFixtures::class]);
        $client->request('GET', '/order');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertSelectorExists('h3', 'Commandes en cours');
    }

    public function testOrderListAndUserRestricted()
    {
        $client = static::createClient();
        $this->loadFixtures([AppFixtures::class]);
        $client= $this->userConnexion($client, 'user');
        $client->request('GET', '/order/list');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertSelectorExists('h3', 'Liste des Commandes');
    }
    
}