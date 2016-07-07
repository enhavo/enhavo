<?php

/**
 * Content.php
 *
 * @since 16/05/16
 * @author gseidel
 */

namespace Enhavo\Bundle\ContentBundle\Entity;

use Enhavo\Bundle\AppBundle\Model\RouteInterface;
use Enhavo\Bundle\AppBundle\Route\Routeable;
use Enhavo\Bundle\AppBundle\Route\Slugable;
use Enhavo\Bundle\ContentBundle\Content\Publishable;
use Enhavo\Bundle\ContentBundle\Sitemap\SitemapInterface;
use Symfony\Cmf\Component\Routing\RouteObjectInterface;

abstract class Content implements Publishable, Routeable, Slugable, SitemapInterface
{
    /**
     * @var \DateTime
     */
    protected $created;

    /**
     * @var \DateTime
     */
    protected $updated;

    /**
     * @var integer
     */
    protected $id;

    /**
     * @var string
     */
    protected $title;

    /**
     * @var \DateTime
     */
    protected $publicationDate;

    /**
     * @var string
     */
    protected $slug;

    /**
     * @var string
     */
    protected $metaDescription;

    /**
     * @var string
     */
    protected $pageTitle;

    /**
     * @var float
     */
    protected $priority;

    /**
     * @var string
     */
    protected $changeFrequency;

    /**
     * @var boolean
     */
    protected $public;

    /**
     * @var RouteInterface
     */
    protected $route;

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
     * Set created
     *
     * @param \DateTime $created
     * @return Content
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set updated
     *
     * @param \DateTime $updated
     * @return Content
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;

        return $this;
    }

    /**
     * Get updated
     *
     * @return \DateTime
     */
    public function getUpdated()
    {
        return $this->updated;
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
     * Set publication_date
     *
     * @param \DateTime $publicationDate
     * @return Content
     */
    public function setPublicationDate(\DateTime $publicationDate = null)
    {
        $this->publicationDate = $publicationDate;

        return $this;
    }

    /**
     * Get publication_date
     *
     * @return \DateTime
     */
    public function getPublicationDate()
    {
        return $this->publicationDate;
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
     * Set priority
     *
     * @param float $priority
     * @return Content
     */
    public function setPriority($priority)
    {
        $this->priority = $priority;

        return $this;
    }

    /**
     * Get priority
     *
     * @return float
     */
    public function getPriority()
    {
        if($this->priority === null) {
            return '0.5';
        }
        return $this->priority;
    }

    /**
     * Set change_frequency
     *
     * @param string $changeFrequency
     * @return Content
     */
    public function setChangeFrequency($changeFrequency)
    {
        $this->changeFrequency = $changeFrequency;

        return $this;
    }

    /**
     * Get change_frequency
     *
     * @return string
     */
    public function getChangeFrequency()
    {
        if($this->changeFrequency === null) {
            return 'never';
        }
        return $this->changeFrequency;
    }

    /**
     * Set public
     *
     * @param boolean $public
     * @return Content
     */
    public function setPublic($public)
    {
        $this->public = $public;

        return $this;
    }

    /**
     * Get public
     *
     * @return boolean
     */
    public function getPublic()
    {
        if($this->public === null) {
            return false;
        }
        return $this->public;
    }

    /**
     * @return RouteInterface
     */
    public function getRoute()
    {
        return $this->route;
    }

    /**
     * @param RouteObjectInterface $route
     */
    public function setRoute(RouteObjectInterface $route)
    {
        $this->route = $route;
    }
}