<?php

namespace Enhavo\Bundle\SettingBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Setting
 */
class Setting
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $container;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Setting
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set container
     *
     * @param string $container
     * @return Setting
     */
    public function setContainer($container)
    {
        $this->container = serialize($container);

        return $this;
    }

    /**
     * Get container
     *
     * @return string 
     */
    public function getContainer()
    {
        if(is_resource($this->container)) {
            $stream = '';
            while($byte = fgets($this->container, 4096)) {
                $stream .= $byte;
            }
            $this->container = unserialize($stream);
        }

        if(is_string($this->container)) {
            $this->container = unserialize($this->container);
        }

        if(is_null($this->container)) {
            $this->container = null;
        }

        return $this->container;
    }
}
