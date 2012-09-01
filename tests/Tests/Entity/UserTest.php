<?php

namespace Tests\Entity;

use Bibi\Entity as Entity;
use Tests as Base;

class UserTest extends \Tests\TestCase
{
    /** @var \Bibi\Entity\User */
    protected $_user;

    public function setUp()
    {
        $this->_user = new Entity\User();
    }

    public function testBirthdate()
    {
        $date = new \DateTime();

        $this->_user->setBirthdate($date);
        $this->assertInstanceOf(
            'DateTime',
            $this->_user->getBirthdate()
        );
        $this->assertEquals($date, $this->_user->getBirthdate());

    }

    public function testEmail()
    {
        $email = 'foo@bar.de';
        $this->_user->setEmail($email);
        $this->assertEquals($email, $this->_user->getEmail());
    }

    public function testLastname()
    {
        $lastname = 'doe';
        $this->_user->setLastname($lastname);
        $this->assertEquals($lastname, $this->_user->getLastname());
    }

    public function testSurname()
    {
        $surname = 'john';
        $this->_user->setSurname($surname);
        $this->assertEquals($surname, $this->_user->getSurname());
    }

    public function testName()
    {
        $name = 'foo';
        $this->_user->setName($name);
        $this->assertEquals($name, $this->_user->getName());
    }
}