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

    public function testGetSimpleObject()
    {
        $this->_entity->setId(1);
        $result = $this->_entity->getSimpleObject();

        $expected = new \stdClass();
        $expected->id = 1;

        $this->assertEquals($expected, $result);
    }
}

/** This exists only to test abstract class */
class Fake extends \Bibi\Entity\Base {
}