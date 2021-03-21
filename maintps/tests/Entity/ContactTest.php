<?php

namespace tests\Entity;

use App\Entity\Contact;
use App\Entity\Provider;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ContactTest extends KernelTestCase
{
    private $contact;
    private $provider;

    public function setup()
    {
        $this->provider = (new Provider())
            ->setUsername("nom essai")
        ;

        $this->contact = (new Contact())
            ->setLastName("Prenom essai")
            ->setFirstName("Nom essai")
            ->setGender(Contact::MALE)
            ->setPhoneNumber("numero")
            ->setEmail("essai@gmail.com")
        ;
    }

    public function assertHasErrors(Contact $contact, int $number = 0)
    {
        self::bootKernel();
        $errors = self::$container->get('validator')->validate($contact);
        $messages = [];
        /** @var constraintsViolation $errors */
        foreach ($errors as $error) {
            $messages[] = $error->getPropertyPath() . '=>' . $error->getMessage();
        }
        $this->assertCount($number, $errors, implode(', ', $messages));
    }

    public function testValidEntity()
    {
        $this->assertHasErrors($this->contact, 0);

        $this->assertEquals("Prenom essai", $this->contact->getLastName());
        $this->assertEquals("Nom essai", $this->contact->getFirstName());
        $this->assertEquals(Contact::MALE, $this->contact->getGender());
        $this->assertEquals("numero", $this->contact->getPhoneNumber());
        $this->assertEquals("essai@gmail.com", $this->contact->getEmail());
    }

    public function testInvalidBlankLastName()
    {
        $this->assertHasErrors($this->contact->setLastName(""), 1);
    }

    public function testInvalidBlankGender()
    {
        $this->assertHasErrors($this->contact->setGender(""), 1);
    }
}

