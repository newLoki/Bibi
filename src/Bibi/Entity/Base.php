<?php

namespace Bibi\Entity;

/**
* @HasLifecycleCallbacks
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
     * @Column(type="datetime")
     * @var \DateTime
     */
    protected $created;

    /**
     * @Column(type="datetime")
     * @var \DateTime
     */
    protected $updated;

    /**
     * Set created date to now, if a new object is constructed
     * (this is not called on hydration)
     *
     * @return void
     */
    public function __construct()
    {
        $this->created = new \DateTime();
        $this->updated = $this->created;
    }

    /**
     * Set updated field to now()
     * @PrePersist
     */
    public function createUpdateDateTime()
    {
        $this->updated = new \DateTime();
    }

    /**
     * Return, when entity was created
     *
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Return when entity was updated last time
     *
     * @return \DateTime
     */
    public function getUpdated()
    {
        return $this->updated;
    }

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
        $this->id = (int) $id;
    }
}