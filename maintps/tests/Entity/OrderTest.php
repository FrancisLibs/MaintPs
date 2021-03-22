<?php

namespace App\tests\Entity;

use App\Entity\User;
use App\Entity\Order;
use App\Entity\Provider;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class OrderTest extends KernelTestCase
{
    private $order;
    private $provider;
    private $user;
    private $date;

    public function setUp()
    {
        $this->provider = (new Provider())
            ->setUserName("Felix")
        ;

        $this->user = (new User())
            ->setUserName('Francis')
        ;
        
        $this->date = new \DateTime('2021-03-18 09:00:00', new \DateTimeZone('Europe/Paris'));
       
        $this->order = (new Order())
            ->setOrderNumber(1)
            ->setExpectedAmount(250)
            ->setDesignation('essai de commande')
            ->setExpectedDeliveryDate(new \DateTime())
            ->setStatus(Order::CLOTUREE)
            ->setProvider($this->provider)
            ->setUser($this->user)
            ->setCreatedAt($this->date)
            ->setExpectedDeliveryDate($this->date)
        ;
    }

    public function assertHasErrors(Order $order, int $number = 0)
    {
        self::bootKernel();
        $errors = self::$container->get('validator')->validate($order);
        $messages = [];
        /** @var constraintsViolation $errors */
        foreach ($errors as $error) {
            $messages[] = $error->getPropertyPath() . '=>' . $error->getMessage();
        }
        $this->assertCount($number, $errors, implode(', ', $messages));
    }

    public function testValidEntity()
    {
        $this->assertHasErrors($this->order, 0);
        $this->assertEquals(1, $this->order->getOrderNumber());
        $this->assertEquals($this->provider, $this->order->getProvider());
        $this->assertEquals($this->user, $this->order->getUser());
        $this->assertEquals($this->date, $this->order->getCreatedAT());
        $this->assertEquals($this->date, $this->order->getExpectedDeliveryDate());
    }

    public function testInvalidBlankOrderNumber()
    {
        $this->assertHasErrors($this->order->setOrderNumber(""), 1);
    }

    public function testInvalidBlankDesignation()
    {
        $this->assertHasErrors($this->order->setDesignation(""), 1);
    }

    public function testInvalidBlankStatus()
    {
        $this->assertHasErrors($this->order->setStatus(""), 1);
    }
}
