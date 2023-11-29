<?php

namespace Enhavo\Bundle\ContentBundle\Model;

class Video
{
    /**
     * @param string $provider
     * @param string $title
     * @param string $description
     * @param string $thumbnail
     * @param string $videoUrl
     * @param string $embedUrl
     */
    public function __construct(
        private string $provider,
        private string $title,
        private string $description,
        private string $thumbnail,
        private string $videoUrl,
        private string $embedUrl,
    )
    {
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
