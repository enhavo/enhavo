<?php

/**
 * Content.php
 *
 * @since 16/05/16
 * @author gseidel
 */

namespace Enhavo\Bundle\ContentBundle\Entity;

use Enhavo\Bundle\AppBundle\Model\Timestampable;
use Enhavo\Bundle\AppBundle\Model\TimestampableTrait;
use Enhavo\Bundle\RoutingBundle\Model\RouteInterface;
use Enhavo\Bundle\RoutingBundle\Model\Routeable;
use Enhavo\Bundle\RoutingBundle\Model\Slugable;
use Enhavo\Bundle\ContentBundle\Content\Publishable;
use Enhavo\Bundle\ContentBundle\Content\PublishableTrait;
use Enhavo\Bundle\ContentBundle\Sitemap\SitemapInterface;
use Enhavo\Bundle\MediaBundle\Model\FileInterface;
use Sylius\Component\Resource\Model\ResourceInterface;

abstract class Content implements Publishable, Routeable, Slugable, SitemapInterface, ResourceInterface, Timestampable
{
    use PublishableTrait;
    use TimestampableTrait;

    /** @var integer */
    protected $id;

    /** @var string */
    protected $title;

    /** @var string */
    protected $slug;

    /** @var string */
    protected $metaDescription;

    /** @var string */
    protected $pageTitle;

    /** @var boolean */
    protected $noIndex = false;

    /** @var boolean */
    protected $noFollow = false;

    /** @var FileInterface */
    protected $openGraphImage;

    /** @var string */
    protected $openGraphTitle;

    /** @var string */
    protected $openGraphDescription;

    /** @var RouteInterface */
    protected $route;

    /** @var string */
    protected $canonicalUrl;

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
     * @return Content
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
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param string $slug
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    }

    /**
     * Set meta_description
     *
     * @param string $metaDescription
     * @return Content
     */
    public function setMetaDescription($metaDescription)
    {
        $this->metaDescription = $metaDescription;

        return $this;
    }

    /**
     * Get meta_description
     *
     * @return string
     */
    public function getMetaDescription()
    {
        return $this->metaDescription;
    }

    /**
     * Set page_title
     *
     * @param string $pageTitle
     * @return Content
     */
    public function setPageTitle($pageTitle)
    {
        $this->pageTitle = $pageTitle;

        return $this;
    }

    /**
     * Get page_title
     *
     * @return string
     */
    public function getPageTitle()
    {
        return $this->pageTitle;
    }

    /**
     * @return bool
     */
    public function isNoIndex()
    {
        return $this->noIndex;
    }

    /**
     * @param bool $noIndex
     */
    public function setNoIndex($noIndex)
    {
        $this->noIndex = $noIndex;
    }

    /**
     * @return bool
     */
    public function isNoFollow()
    {
        return $this->noFollow;
    }

    /**
     * @param bool $noFollow
     */
    public function setNoFollow($noFollow)
    {
        $this->noFollow = $noFollow;
    }

    /**
     * @return RouteInterface
     */
    public function getRoute()
    {
        return $this->route;
    }

    /**
     * @param RouteInterface $route
     * @return void
     */
    public function setRoute(RouteInterface $route)
    {
        $this->route = $route;
    }

    /**
     * Get public
     *
     * @return boolean
     */
    public function getPublic()
    {
        return $this->public;
    }

    /**
     * Set openGraphTitle
     *
     * @param string $openGraphTitle
     * @return Content
     */
    public function setOpenGraphTitle($openGraphTitle)
    {
        $this->openGraphTitle = $openGraphTitle;

        return $this;
    }

    /**
     * Get openGraphTitle
     *
     * @return string
     */
    public function getOpenGraphTitle()
    {
        return $this->openGraphTitle;
    }

    /**
     * Set openGraphDescription
     *
     * @param string $openGraphDescription
     * @return Content
     */
    public function setOpenGraphDescription($openGraphDescription)
    {
        $this->openGraphDescription = $openGraphDescription;

        return $this;
    }

    /**
     * Get openGraphDescription
     *
     * @return string
     */
    public function getOpenGraphDescription()
    {
        return $this->openGraphDescription;
    }

    /**
     * Set openGraphImage
     *
     * @param \Enhavo\Bundle\MediaBundle\Entity\File $openGraphImage
     * @return Content
     */
    public function setOpenGraphImage(\Enhavo\Bundle\MediaBundle\Entity\File $openGraphImage = null)
    {
        $this->openGraphImage = $openGraphImage;

        return $this;
    }

    /**
     * Get openGraphImage
     *
     * @return \Enhavo\Bundle\MediaBundle\Model\FileInterface
     */
    public function getOpenGraphImage()
    {
        return $this->openGraphImage;
    }

    public function __toString()
    {
        return (string)$this->getTitle();
    }

    /**
     * @return string|null
     */
    public function getCanonicalUrl(): ?string
    {
        return $this->canonicalUrl;
    }

    /**
     * @param string|null $canonicalUrl
     */
    public function setCanonicalUrl(?string $canonicalUrl): void
    {
        $this->canonicalUrl = $canonicalUrl;
    }
}
