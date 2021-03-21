<?php

namespace App\tests\Entity;

use App\Entity\Order;
use App\Entity\Account;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class AccountTest extends KernelTestCase
{
    private $order;
    private $account;

    public function setUp()
    {
        $this->order = (new Order())
            ->setOrderNumber("UnNumeroAuHasard")
            ->setDesignation("order d'essai")
            ->setStatus(Order::EN_ATTENTE)
        ;

        $this->account = (new Account())
            ->setDesignation("pièces détachées")
            ->setLetter("D")
            ->setAccountNumber(6515)
            ->addOrder($this->order)
        ;
    }

    public function assertHasErrors(Account $account, int $number = 0)
    {
        self::bootKernel();
        $errors = self::$container->get('validator')->validate($account);
        $messages = [];
        /** @var constraintsViolation $errors */
        foreach ($errors as $error) {
            $messages[] = $error->getPropertyPath() . '=>' . $error->getMessage();
        }
        $this->assertCount($number, $errors, implode(', ', $messages));
    }

    public function testValidEntity()
    {
        $this->assertHasErrors($this->account, 0);

        $this->assertEquals(6515, $this->account->getAccountNumber());
        $this->assertEquals("pièces détachées", $this->account->getDesignation());
        $this->assertEquals(Order::EN_ATTENTE, $this->order->getStatus());
    }

    public function testInvalidBlankDesignation()
    {
        $this->assertHasErrors($this->account->setDesignation(""), 1);
    }

    public function testInvalidBlankAccountNumber()
    {
        $this->assertHasErrors($this->account->setAccountNumber(""), 1);
    }

    public function testInvalidBlankLetter()
    {
        $this->assertHasErrors($this->account->setLetter(""), 1);
    }

    public function testAddAndRemoveOrder()
    {
        $this->account->addOrder($this->order);

        $orderCollection = $this->account->getOrders();
        $this->assertEquals(1, count($orderCollection));
        
        foreach ($orderCollection as $order) {
            $this->account->removeOrder($order);
        }

        $ordersCollection = $this->account->getOrders();
        $this->assertEquals(0, count($ordersCollection));
    }
}

    