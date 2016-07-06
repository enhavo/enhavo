<?php

namespace Enhavo\Bundle\ContentBundle\Model;

/**
 * SitemapImage.php
 *
 * @since 05/07/16
 * @author gseidel
 */
class SitemapImage
{
    private $location;

    /**
     * @return mixed
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * @param mixed $location
     */
    public function setLocation($location)
    {
        $this->location = $location;
    }
}