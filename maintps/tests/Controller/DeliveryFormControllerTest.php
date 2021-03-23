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

    public function testCreateDeliveryFormDisplay()
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

    public function testCreateDeliveryForm()
    {
        $client = static::createClient();
        $this->loadFixtures([AppFixtures::class]);
        $client= $this->userConnexion($client, 'user');
        $order = self::$container->get(OrderRepository::class)->find(1);
        $crawler = $client->request('GET', '/deliveryform/create/' . $order->getId());
        // Remplissage formulaire
        $form = $crawler->selectButton('Ajouter')->form();
        $date = new \DateTime();
        $form['delivery_form[deliveryFormNumber]']= 'TestNumber';
        $form['delivery_form[deliveryFormDate]'] ="2021-03-22 05:59:28";
        $crawler = $client->submit($form);
        $client->followRedirect();
        $this->assertSelectorExists('h2.titreShowOrder', 'Commande R2PM40-' . $order->getOrderNumber());

    }


}