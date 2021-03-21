<?php

namespace tests\Entity;

use App\Entity\Invoice;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class InvoiceTest extends KernelTestCase
{
    private $invoice;
    private $date;

    public function setUp()
    {
        $this->date = new \DateTime();
        $this->invoice = (new Invoice())
            ->setAmount(100.25)
            ->setInvoiceDate($this->date)
            ->setInvoiceNumber("Numero de test")
        ;
    }

    public function assertHasErrors(Invoice $invoice, int $number = 0)
    {
        self::bootKernel();
        $errors = self::$container->get('validator')->validate($invoice);
        $messages = [];
        /** @var constraintsViolation $errors */
        foreach ($errors as $error) {
            $messages[] = $error->getPropertyPath() . '=>' . $error->getMessage();
        }
        $this->assertCount($number, $errors, implode(', ', $messages));
    }

    public function testValidEntity()
    {
        $this->assertHasErrors($this->invoice, 0);

        $this->assertEquals(100.25, $this->invoice->getAmount());
        $this->assertEquals($this->date, $this->invoice->getInvoiceDate());
        $this->assertEquals("Numero de test", $this->invoice->getInvoiceNumber());
    }
}