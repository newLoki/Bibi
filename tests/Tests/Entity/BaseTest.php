<?php

namespace Tests\Entity;

use Bibi\Entity as Entity;
use Tests as Base;

class BaseTest extends \Tests\TestCase
{
    /** @var Fake */
    protected $_entity;

    public function setUp()
    {
        $this->_entity = new Fake();
    }

    public function testCreatedTime()
    {
        $this->assertInstanceOf(
            'DateTime',
            $this->_entity->getCreated()
        );
    }

    public function testUpdatesTimeAfterCreate()
    {
        $this->assertInstanceOf(
            'DateTime',
            $this->_entity->getUpdated()
        );
        $this->assertEquals($this->_entity->getCreated(), $this->_entity->getUpdated());
    }

    public function testUpdatesTimeAfterUpdate()
    {
        sleep(1); //else update time is same as created, because this is seconds based
        $this->_entity->createUpdateDateTime();
        $this->assertInstanceOf(
            'DateTime',
            $this->_entity->getUpdated()
        );

        $this->assertNotEquals($this->_entity->getCreated(), $this->_entity->getUpdated());
    }

    public function testId()
    {
        $this->_entity->setId(1);
        $this->assertEquals(1, $this->_entity->getId());
    }
}

/** This exists only to test abstract class */
class Fake extends \Bibi\Entity\Base {
}