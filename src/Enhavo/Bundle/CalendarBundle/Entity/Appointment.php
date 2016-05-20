<?php

namespace Enhavo\Bundle\CalendarBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Enhavo\Bundle\ContentBundle\Entity\Content;

/**
 * Appointment
 */
class Appointment extends Content
{
    /**
     * @var string
     */
    private $teaser;

    /**
     * @var \DateTime
     */
    private $dateFrom;

    /**
     * @var \DateTime
     */
    private $dateTo;

    /**
     * @var \Enhavo\Bundle\GridBundle\Entity\Grid
     */
    private $grid;

    /**
     * @var \Enhavo\Bundle\MediaBundle\Entity\File
     */
    private $picture;

    /**
     * Set teaser
     *
     * @param string $teaser
     *
     * @return Appointment
     */
    public function setTeaser($teaser)
    {
        $this->teaser = $teaser;

        return $this;
    }

    /**
     * Get teaser
     *
     * @return string
     */
    public function getTeaser()
    {
        return $this->teaser;
    }

    /**
     * Set dateFrom
     *
     * @param \DateTime $dateFrom
     *
     * @return Appointment
     */
    public function setDateFrom($dateFrom)
    {
        $this->dateFrom = $dateFrom;

        return $this;
    }

    /**
     * Get dateFrom
     *
     * @return \DateTime
     */
    public function getDateFrom()
    {
        return $this->dateFrom;
    }

    /**
     * Set dateTo
     *
     * @param \DateTime $dateTo
     *
     * @return Appointment
     */
    public function setDateTo($dateTo)
    {
        $this->dateTo = $dateTo;

        return $this;
    }

    /**
     * Get dateTo
     *
     * @return \DateTime
     */
    public function getDateTo()
    {
        return $this->dateTo;
    }

    /**
     * Set content
     *
     * @param \Enhavo\Bundle\GridBundle\Entity\Grid $grid
     * @return Appointment
     */
    public function setGrid(\Enhavo\Bundle\GridBundle\Entity\Grid $grid = null)
    {
        $this->grid = $grid;

        return $this;
    }

    /**
     * Get content
     *
     * @return \Enhavo\Bundle\GridBundle\Entity\Grid
     */
    public function getGrid()
    {
        return $this->grid;
    }

    /**
     * @return \Enhavo\Bundle\MediaBundle\Entity\File
     */
    public function getPicture()
    {
        return $this->picture;
    }

    /**
     * @param \Enhavo\Bundle\MediaBundle\Entity\File $picture
     */
    public function setPicture($picture)
    {
        $this->picture = $picture;
    }
}
