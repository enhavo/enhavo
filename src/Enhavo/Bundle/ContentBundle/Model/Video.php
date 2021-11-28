<?php

namespace Enhavo\Bundle\ContentBundle\Model;

class Video
{
    /** @var string */
    private $provider;

    /** @var string */
    private $title;

    /** @var string */
    private $description;

    /** @var string */
    private $thumbnail;

    /** @var string */
    private $videoUrl;

    /** @var string */
    private $embedUrl;

    /**
     * @param string $provider
     * @param string $title
     * @param string $description
     * @param string $thumbnail
     * @param string $videoUrl
     * @param string $embedUrl
     */
    public function __construct(string $provider, string $title, string $description, string $thumbnail, string $videoUrl, string $embedUrl)
    {
        $this->provider = $provider;
        $this->title = $title;
        $this->description = $description;
        $this->thumbnail = $thumbnail;
        $this->videoUrl = $videoUrl;
        $this->embedUrl = $embedUrl;
    }

    /**
     * @return string
     */
    public function getProvider(): string
    {
        return $this->provider;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @return string
     */
    public function getThumbnail(): string
    {
        return $this->thumbnail;
    }

    /**
     * @return string
     */
    public function getVideoUrl(): string
    {
        return $this->videoUrl;
    }

    /**
     * @return string
     */
    public function getEmbedUrl(): string
    {
        return $this->embedUrl;
    }
}
