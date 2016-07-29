<?php

namespace Enhavo\Bundle\CalendarBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Enhavo\Bundle\ContentBundle\Entity\Content;
use Enhavo\Bundle\GridBundle\Model\GridInterface;
use Enhavo\Bundle\MediaBundle\Model\FileInterface;

/**
 * Appointment
 */
class Appointment extends Content
{
    /**
     * @var string
     */
    protected $teaser;

    /**
     * @var \DateTime
     */
    protected $dateFrom;

    /**
     * @var \DateTime
     */
    protected $dateTo;

    /**
     * @var GridInterface
     */
    protected $grid;

    /**
     * @var FileInterface
     */
    protected $picture;

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
     * @param GridInterface $grid
     * @return Appointment
     */
    public function setGrid(GridInterface $grid = null)
    {
        $this->grid = $grid;

        return $this;
    }

    /**
     * Get content
     *
     * @return GridInterface
     */
    public function getGrid()
    {
        return $this->grid;
    }

    /**
     * @return FileInterface
     */
    public function getPicture()
    {
        return $this->picture;
    }

    /**
     * @param FileInterface|null $picture
     */
    public function setPicture(FileInterface $picture = null)
    {
        $this->picture = $picture;
    }
}
