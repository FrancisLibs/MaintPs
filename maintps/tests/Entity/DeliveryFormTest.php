<?php

namespace App\tests\Entity;

use App\Entity\Order;
use App\Entity\Invoice;
use App\Entity\DeliveryForm;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class DeliveryFormTest extends KernelTestCase
{
    private $deliveryForm;
    private $invoice;
    private $order;
    private $date;

    public function setUp()
    {
        $this->invoice = (new Invoice())
            ->setInvoiceNumber("numero essai")
            ->setInvoiceDate(new \DateTime())
            ->setAmount(523,25)
        ;

        $this->order = (new Order())
            ->setOrderNumber("Numero d'essai")
            ->setDesignation("Facture d'essai")
            ->setStatus(Order::EN_COURS)
        ;

        $this->date = new \DateTime('2021-03-18 09:00:00', new \DateTimeZone('Europe/Paris'));
        $this->deliveryForm = (new DeliveryForm())
            ->setDeliveryFormNumber("200")
            ->setDeliveryFormDate($this->date)
            ->setOrder($this->order)
        ;
    }

    public function assertHasErrors(DeliveryForm $deliveryForm, int $number = 0)
    {
        self::bootKernel();
        $errors = self::$container->get('validator')->validate($deliveryForm);
        $messages = [];
        /** @var constraintsViolation $errors */
        foreach ($errors as $error) {
            $messages[] = $error->getPropertyPath() . '=>' . $error->getMessage();
        }
        $this->assertCount($number, $errors, implode(', ', $messages));
    }

    public function testValidEntity()
    {
        $this->assertHasErrors($this->deliveryForm, 0);

        $this->assertEquals("200", $this->deliveryForm->getDeliveryFormNumber());
        $this->assertEquals($this->date, $this->deliveryForm->getDeliveryFormDate());
        $this->assertEquals($this->order, $this->deliveryForm->getOrder());
    }

    public function testInvalidBlankDeliveryFormNumber()
    {
        $this->assertHasErrors($this->deliveryForm->setDeliveryFormNumber(""), 1);
    }
}