<?php

namespace Enhavo\Bundle\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Session
 */
class Session
{

    /**
     * @var string
     */
    private $id;

    /**
     * @var string
     */
    private $value;

    /**
     * @var integer
     */
    private $time;

    /**
     * @var integer
     */
    private $lifetime;

    /**
     * Set id
     *
     * @param string $id
     * @return Session
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get id
     *
     * @return string 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set value
     *
     * @param string $value
     * @return Session
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return string 
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set time
     *
     * @param integer $time
     * @return Session
     */
    public function setTime($time)
    {
        $this->time = $time;

        return $this;
    }

    /**
     * Get time
     *
     * @return integer 
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * Set lifetime
     *
     * @param integer $lifetime
     *
     * @return Session
     */
    public function setLifetime($lifetime)
    {
        $this->lifetime = $lifetime;

        return $this;
    }

    /**
     * Get lifetime
     *
     * @return integer
     */
    public function getLifetime()
    {
        return $this->lifetime;
    }
}
