<?php

namespace Bibi\Entity;

/**
 * @MappedSuperclass
 */
abstract class Base
{
    const DATE_FORMAT = 'Y-m-d\TH:i:s';

    /**
     * @Id
     * @Column(type="integer")
     * @GeneratedValue(strategy="AUTO")
     * @var int
     **/
    protected $id;

    /**
     * Return unique identifier for entity
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set id for this entity (commonly for testing reasons)
     *
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = (int)$id;
    }

    public function getSimpleObject()
    {
        $result = new \stdClass();

        foreach (get_class_vars(get_class($this)) as $key => $value) {
            $methodName = "get" . ucfirst($key);
            $result->{$key} = call_user_func(array($this, $methodName));
        }

        return $result;
    }
}