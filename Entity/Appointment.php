<?php

namespace esperanto\ProjectBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Appointment
 */
class Appointment
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $text;

    /**
     * @var \DateTime
     */
    private $date;

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
     * Set title
     *
     * @param string $title
     * @return Appointment
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set teaser
     *
     * @param string $teaser
     * @return Appointment
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Get teaser
     *
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     * @return Appointment
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    public function getDay()
    {
        return $this->getDate()->format('d');
    }

    public function getMonth()
    {
        return $this->getDate()->format('M').".".$this->getDate()->format('Y');
    }

    public function getTime()
    {
        return $this->getDate()->format('H:i');
    }

}
