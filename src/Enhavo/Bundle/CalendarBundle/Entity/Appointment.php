<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\CalendarBundle\Entity;

use Enhavo\Bundle\BlockBundle\Model\NodeInterface;
use Enhavo\Bundle\CalendarBundle\Model\AppointmentInterface;
use Enhavo\Bundle\ContentBundle\Entity\Content;
use Enhavo\Bundle\MediaBundle\Model\FileInterface;

/**
 * Appointment
 */
class Appointment extends Content implements AppointmentInterface
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
     * @var NodeInterface
     */
    protected $content;

    /**
     * @var FileInterface
     */
    protected $picture;

    /**
     * @var string
     */
    protected $externalId;

    /**
     * @var string
     */
    protected $locationLongitude;

    /**
     * @var string
     */
    protected $locationLatitude;

    /**
     * @var string
     */
    protected $locationName;

    /**
     * @var string
     */
    protected $locationCity;

    /**
     * @var string
     */
    protected $locationCountry;

    /**
     * @var string
     */
    protected $locationStreet;

    /**
     * @var string
     */
    protected $locationZip;

    /**
     * @var string
     */
    protected $repeatRule;

    /**
     * @var string
     */
    protected $importerName;

    /**
     * @var bool
     */
    protected $notImporterHandled;

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
     * @return Appointment
     */
    public function setContent(?NodeInterface $content = null)
    {
        $this->content = $content;
        if ($content) {
            $content->setType(NodeInterface::TYPE_ROOT);
            $content->setProperty('content');
            $content->setResource($this);
        }

        return $this;
    }

    /**
     * Get content
     *
     * @return NodeInterface
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @return FileInterface
     */
    public function getPicture()
    {
        return $this->picture;
    }

    public function setPicture(?FileInterface $picture = null)
    {
        $this->picture = $picture;
    }
    /**
     * @var string
     */
    private $condition;

    /**
     * Set externalId
     *
     * @param string $externalId
     *
     * @return Appointment
     */
    public function setExternalId($externalId)
    {
        $this->externalId = $externalId;

        return $this;
    }

    /**
     * Get externalId
     *
     * @return string
     */
    public function getExternalId()
    {
        return $this->externalId;
    }

    /**
     * Set condition
     *
     * @param string $condition
     *
     * @return Appointment
     */
    public function setCondition($condition)
    {
        $this->condition = $condition;

        return $this;
    }

    /**
     * Get condition
     *
     * @return string
     */
    public function getCondition()
    {
        return $this->condition;
    }

    /**
     * Set locationLongitude
     *
     * @param string $locationLongitude
     *
     * @return Appointment
     */
    public function setLocationLongitude($locationLongitude)
    {
        $this->locationLongitude = $locationLongitude;

        return $this;
    }

    /**
     * Get locationLongitude
     *
     * @return string
     */
    public function getLocationLongitude()
    {
        return $this->locationLongitude;
    }

    /**
     * Set locationLatitude
     *
     * @param string $locationLatitude
     *
     * @return Appointment
     */
    public function setLocationLatitude($locationLatitude)
    {
        $this->locationLatitude = $locationLatitude;

        return $this;
    }

    /**
     * Get locationLatitude
     *
     * @return string
     */
    public function getLocationLatitude()
    {
        return $this->locationLatitude;
    }

    /**
     * Set locationName
     *
     * @param string $locationName
     *
     * @return Appointment
     */
    public function setLocationName($locationName)
    {
        $this->locationName = $locationName;

        return $this;
    }

    /**
     * Get locationName
     *
     * @return string
     */
    public function getLocationName()
    {
        return $this->locationName;
    }

    /**
     * Set locationCity
     *
     * @param string $locationCity
     *
     * @return Appointment
     */
    public function setLocationCity($locationCity)
    {
        $this->locationCity = $locationCity;

        return $this;
    }

    /**
     * Get locationCity
     *
     * @return string
     */
    public function getLocationCity()
    {
        return $this->locationCity;
    }

    /**
     * Set locationCountry
     *
     * @param string $locationCountry
     *
     * @return Appointment
     */
    public function setLocationCountry($locationCountry)
    {
        $this->locationCountry = $locationCountry;

        return $this;
    }

    /**
     * Get locationCountry
     *
     * @return string
     */
    public function getLocationCountry()
    {
        return $this->locationCountry;
    }

    /**
     * Set locationStreet
     *
     * @param string $locationStreet
     *
     * @return Appointment
     */
    public function setLocationStreet($locationStreet)
    {
        $this->locationStreet = $locationStreet;

        return $this;
    }

    /**
     * Get locationStreet
     *
     * @return string
     */
    public function getLocationStreet()
    {
        return $this->locationStreet;
    }

    /**
     * Set locationZip
     *
     * @param string $locationZip
     *
     * @return Appointment
     */
    public function setLocationZip($locationZip)
    {
        $this->locationZip = $locationZip;

        return $this;
    }

    /**
     * Get locationZip
     *
     * @return string
     */
    public function getLocationZip()
    {
        return $this->locationZip;
    }

    /**
     * Set repeatRule
     *
     * @param string $repeatRule
     *
     * @return Appointment
     */
    public function setRepeatRule($repeatRule)
    {
        $this->repeatRule = $repeatRule;

        return $this;
    }

    /**
     * Get repeatRule
     *
     * @return string
     */
    public function getRepeatRule()
    {
        return $this->repeatRule;
    }

    /**
     * Set importerName
     *
     * @param string $importerName
     *
     * @return Appointment
     */
    public function setImporterName($importerName)
    {
        $this->importerName = $importerName;

        return $this;
    }

    /**
     * Get importerName
     *
     * @return string
     */
    public function getImporterName()
    {
        return $this->importerName;
    }

    /**
     * Set notImporterHandled
     *
     * @param bool $notImporterHandled
     *
     * @return Appointment
     */
    public function setNotImporterHandled($notImporterHandled)
    {
        $this->notImporterHandled = $notImporterHandled;

        return $this;
    }

    /**
     * Get notImporterHandled
     *
     * @return bool
     */
    public function getNotImporterHandled()
    {
        return $this->notImporterHandled;
    }
}
