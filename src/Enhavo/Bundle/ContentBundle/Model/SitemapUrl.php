<?php

namespace Enhavo\Bundle\ContentBundle\Model;

/**
 * SitemapUrl.php
 *
 * @since 05/07/16
 * @author gseidel
 */
class SitemapUrl
{
    /**
     * @var string
     */
    private $location;

    /**
     * @var \DateTime
     */
    private $lastModified;

    /**
     * @var SitemapImage[]
     */
    private $images = [];

    /**
     * @var SitemapVideo[]
     */
    private $videos = [];

    /**
     * @return string
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * @param string $location
     */
    public function setLocation($location)
    {
        $this->location = $location;
    }

    /**
     * @return \DateTime
     */
    public function getLastModified()
    {
        return $this->lastModified;
    }

    /**
     * @param \DateTime $lastModified
     */
    public function setLastModified($lastModified)
    {
        $this->lastModified = $lastModified;
    }

    /**
     * @return SitemapImage[]
     */
    public function getImages()
    {
        return $this->images;
    }

    /**
     * @param SitemapImage[] $images
     */
    public function setImages($images)
    {
        $this->images = $images;
    }

    /**
     * @param SitemapImage[] $image
     */
    public function addImage($image)
    {
        $this->images[] = $image;
    }

    /**
     * @return SitemapVideo[]
     */
    public function getVideos()
    {
        return $this->videos;
    }

    /**
     * @param SitemapVideo[] $videos
     */
    public function setVideos($videos)
    {
        $this->videos = $videos;
    }

    /**
     * @param SitemapVideo[] $image
     */
    public function addVideo($video)
    {
        $this->videos[] = $video;
    }
}
