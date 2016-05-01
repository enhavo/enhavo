<?php

namespace Enhavo\Bundle\SearchBundle\Entity;

/**
 * CronTask
 */
class CronTask
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
     * @var array
     */
    private $commands;

    /**
     * @var integer
     */
    private $ival;

    /**
     * @var \DateTime
     */
    private $lastrun;


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
     *
     * @return CronTask
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
     * Set commands
     *
     * @param array $commands
     *
     * @return CronTask
     */
    public function setCommands($commands)
    {
        $this->commands = $commands;

        return $this;
    }

    /**
     * Get commands
     *
     * @return array
     */
    public function getCommands()
    {
        return $this->commands;
    }

    /**
     * Set ival
     *
     * @param integer $ival
     *
     * @return CronTask
     */
    public function setIval($ival)
    {
        $this->ival = $ival;

        return $this;
    }

    /**
     * Get ival
     *
     * @return integer
     */
    public function getIval()
    {
        return $this->ival;
    }

    /**
     * Set lastrun
     *
     * @param \DateTime $lastrun
     *
     * @return CronTask
     */
    public function setLastrun($lastrun)
    {
        $this->lastrun = $lastrun;

        return $this;
    }

    /**
     * Get lastrun
     *
     * @return \DateTime
     */
    public function getLastrun()
    {
        return $this->lastrun;
    }
}

