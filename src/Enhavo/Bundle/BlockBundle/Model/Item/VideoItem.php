<?php

namespace Enhavo\Bundle\GridBundle\Model\Item;

use Enhavo\Bundle\GridBundle\Entity\AbstractItem;

/**
 * Video
 */
class VideoItem extends AbstractItem
{
    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $url;

    /**
     * Set title
     *
     * @param string $title
     *
     * @return VideoItem
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
     * Set url
     *
     * @param string $url
     *
     * @return VideoItem
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }
}

