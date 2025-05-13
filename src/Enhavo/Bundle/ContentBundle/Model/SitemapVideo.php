<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\ContentBundle\Model;

/**
 * SitemapVideo.php
 *
 * @since 05/07/16
 *
 * @author gseidel
 */
class SitemapVideo
{
    /**
     * @var string
     */
    private $thumbnailLocation;

    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $description;

    /**
     * @var string
     */
    private $contentLocation;

    /**
     * @var string
     */
    private $playerLocation;

    /**
     * @var string
     */
    private $duration;

    /**
     * @var \DateTime
     */
    private $expirationDate;

    /**
     * @return string
     */
    public function getThumbnailLocation()
    {
        return $this->thumbnailLocation;
    }

    /**
     * @param string $thumbnailLocation
     */
    public function setThumbnailLocation($thumbnailLocation)
    {
        $this->thumbnailLocation = $thumbnailLocation;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getContentLocation()
    {
        return $this->contentLocation;
    }

    /**
     * @param string $contentLocation
     */
    public function setContentLocation($contentLocation)
    {
        $this->contentLocation = $contentLocation;
    }

    /**
     * @return string
     */
    public function getPlayerLocation()
    {
        return $this->playerLocation;
    }

    /**
     * @param string $playerLocation
     */
    public function setPlayerLocation($playerLocation)
    {
        $this->playerLocation = $playerLocation;
    }

    /**
     * @return string
     */
    public function getDuration()
    {
        return $this->duration;
    }

    /**
     * @param string $duration
     */
    public function setDuration($duration)
    {
        $this->duration = $duration;
    }

    /**
     * @return \DateTime
     */
    public function getExpirationDate()
    {
        return $this->expirationDate;
    }

    /**
     * @param \DateTime $expirationDate
     */
    public function setExpirationDate($expirationDate)
    {
        $this->expirationDate = $expirationDate;
    }
}
