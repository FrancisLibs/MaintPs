<?php

namespace tests\Controller;

use App\DataFixtures\AppFixtures;
use App\Repository\OrderRepository;
use App\Tests\Controller\ConnectUserTrait;
use Symfony\Component\HttpFoundation\Response;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DeliveryFormControllerTest extends WebTestCase
{
    use ConnectUserTrait;
    use FixturesTrait;

    public function testCreateDeliveryForm()
    {
        $client = static::createClient();
        $this->loadFixtures([AppFixtures::class]);
        $client= $this->userConnexion($client, 'admin');
        $order = self::$container->get(OrderRepository::class)->find(1);
        
        $client->request('GET', '/deliveryform/create/' . $order->getId());
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertSelectorExists('h1', 'Ajout BL');
    }

    public function testCreateDeliveryFormIsUserRestricted()
    {
        $client = static::createClient();
        $this->loadFixtures([AppFixtures::class]);
        $client= $this->userConnexion($client, 'user');
        $order = self::$container->get(OrderRepository::class)->find(1);
        
        $client->request('GET', '/deliveryform/create/' . $order->getId());
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertSelectorExists('h1', 'Ajout BL');
    }

    
}