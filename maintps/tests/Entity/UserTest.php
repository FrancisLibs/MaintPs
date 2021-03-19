<?php

namespace App\tests\Entity;

use App\Entity\Task;
use App\Entity\User;
use App\Entity\Order;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UserTest extends KernelTestCase
{
    private $user;
    private $order;

    public function setUp()
    {
        $this->user = (new User())
            ->setUsername('UtilisateurTest')
            ->setRoles(["ROLE_USER"])
            ->setPassword('password')
            ->setEmail('utilTest@gmail.com')
            ->setPhoneNumber("0687823380")
            ->setFirstName("Francis")
            ->setLastName("Libs")
        ;
   
        $this->order = (new Order())
            ->setorderNumber("Numero d'essai")
            ->setDesignation("Facture d'essai")
            ->setStatus(Order::EN_COURS)
            ->setUser($this->user)
        ;
    }

    public function assertHasErrors(User $user, int $number = 0)
    {
        self::bootKernel();
        $errors = self::$container->get('validator')->validate($user);
        $messages = [];
        /** @var constraintsViolation $errors */
        foreach ($errors as $error) {
            $messages[] = $error->getPropertyPath() . '=>' . $error->getMessage();
        }
        $this->assertCount($number, $errors, implode(', ', $messages));
    }

    public function testValidEntity()
    {
        $this->assertHasErrors($this->user, 0);
        $this->assertEquals("UtilisateurTest", $this->user->getUserName());
        $this->assertEquals(["ROLE_USER"], $this->user->getRoles());
        $this->assertEquals("password", $this->user->getPassword());
        $this->assertEquals("0687823380", $this->user->getPhoneNumber());
        $this->assertEquals("Francis", $this->user->getFirstName());
        $this->assertEquals("Libs", $this->user->getLastName());
    }

    public function testAddAndRemoveOrder()
    {
        $this->user->addOrder($this->order);
        $this->assertEquals($this->user, $this->order->getUser());

        $orderCollection = $this->user->getOrders();
        $this->assertEquals(1, count($orderCollection));
        
        foreach ($orderCollection as $order) {
            $this->user->removeOrder($order);
        }

        $ordersCollection = $this->user->getOrders();
        $this->assertEquals(0, count($ordersCollection));
        $this->assertEquals(null, $this->order->getUser());
    }

    public function testMinLenghtUsername()
    {
        $this->assertHasErrors($this->user->setUsername("j"), 1);
    }

    public function testBlankUsername()
    {
        $this->assertHasErrors($this->user->setUsername(""), 2);
    }

    public function testMinLenghtPassword()
    {
        $this->assertHasErrors($this->user->setPassword("jkkkk"), 1);
    }

    public function testBlankPassword()
    {
        $this->assertHasErrors($this->user->setPassword(""), 2);
    }

    public function testBlankEmail()
    {
        $this->assertHasErrors($this->user->setEmail(""), 1);
    }

    public function testValidEmail()
    {
        $this->assertHasErrors($this->user->setEmail("d@f"), 1);
    }

    public function testEmailPattern()
    {
        $this->assertHasErrors($this->user->setEmail("d@f.com"), 0);
    }
}
