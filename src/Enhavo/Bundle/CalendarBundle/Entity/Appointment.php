<?php

namespace Enhavo\Bundle\CalendarBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use BaconStringUtils\Slugifier;

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
     * @var \Enhavo\Bundle\GridBundle\Entity\Content
     */
    private $content;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $picture;

    /**
     * @var string
     */
    private $slug;

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
     *
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
     * Constructor
     */
    public function __construct()
    {
        $this->picture = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set content
     *
     * @param \Enhavo\Bundle\GridBundle\Entity\Content $content
     *
     * @return Appointment
     */
    public function setContent(\Enhavo\Bundle\GridBundle\Entity\Content $content = null)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return \Enhavo\Bundle\GridBundle\Entity\Content
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Add picture
     *
     * @param \Enhavo\Bundle\MediaBundle\Entity\File $picture
     *
     * @return Appointment
     */
    public function addPicture(\Enhavo\Bundle\MediaBundle\Entity\File $picture)
    {
        $this->picture[] = $picture;

        return $this;
    }

    /**
     * Remove picture
     *
     * @param \Enhavo\Bundle\MediaBundle\Entity\File $picture
     */
    public function removePicture(\Enhavo\Bundle\MediaBundle\Entity\File $picture)
    {
        $this->picture->removeElement($picture);
    }

    /**
     * Get picture
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPicture()
    {
        return $this->picture;
    }

    /**
     * Set slug
     *
     * @param string $slug
     * @return Calendar
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug()
    {
        if(empty($this->slug)) {
            $this->slug = '';
            if($this->getTitle()) {
                $slugifier = new Slugifier;
                $this->slug = $slugifier->slugify($this->getTitle());
            }
        }
        return $this->slug;
    }

}
