<?php

namespace tests\Entity;

use App\Entity\Provider;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ProviderTest extends KernelTestCase
{
    private $provider;

    public function setUp()
    {
        $this->provider = (new Provider())
            ->setUsername("testName")
            ->setEmail("testEmail@gmail.com")
            ->setphoneNumber("0687823380")
            ->setAdress("6 rue Frédéric Chopin")
            ->setZipCode("67116")
            ->setLocality("Reichstett")
            ->setState("France")
            ->setNote(7)
            ->setActivity("activité essai")
        ;
    }

    public function assertHasErrors(Provider $provider, int $number = 0)
    {
        self::bootKernel();
        $errors = self::$container->get('validator')->validate($provider);
        $messages = [];
        /** @var constraintsViolation $errors */
        foreach ($errors as $error) {
            $messages[] = $error->getPropertyPath() . '=>' . $error->getMessage();
        }
        $this->assertCount($number, $errors, implode(', ', $messages));
    }

    public function testValidEntity()
    {
        $this->assertHasErrors($this->provider, 0);

        $this->assertEquals("testName", $this->provider->getUserName());
        $this->assertEquals("testEmail@gmail.com", $this->provider->getEmail());
        $this->assertEquals("0687823380", $this->provider->getPhoneNumber());
        $this->assertEquals("6 rue Frédéric Chopin", $this->provider->getAdress());
        $this->assertEquals("67116", $this->provider->getZipCode());
        $this->assertEquals("Reichstett", $this->provider->getLocality());
        $this->assertEquals("France", $this->provider->getState());
        $this->assertEquals(7, $this->provider->getNote());
        $this->assertEquals("activité essai", $this->provider->getActivity());
    }

    public function testInvalidBlankOrderNumber()
    {
        $this->assertHasErrors($this->provider->setUsername(""), 1);
    }

    public function testInvalidEmail()
    {
        $this->assertHasErrors($this->provider->setEmail("fr.libs"), 1);
    }

    public function testInvalidNote()
    {
        $this->assertHasErrors($this->provider->setNote(1), 0);
        $this->assertHasErrors($this->provider->setNote(11), 1);
        $this->assertHasErrors($this->provider->setNote(-1), 1);

    }
}
