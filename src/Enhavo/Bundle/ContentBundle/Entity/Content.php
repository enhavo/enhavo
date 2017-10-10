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
use Enhavo\Bundle\ContentBundle\Content\PublishableTrait;
use Enhavo\Bundle\ContentBundle\Sitemap\SitemapInterface;
use Enhavo\Bundle\MediaBundle\Model\FileInterface;
use Sylius\Component\Resource\Model\ResourceInterface;
use Symfony\Cmf\Component\Routing\RouteObjectInterface;

abstract class Content implements Publishable, Routeable, Slugable, SitemapInterface, ResourceInterface
{
    use PublishableTrait;
    
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
     * @var FileInterface
     */
    protected $openGraphImage;

    /**
     * @var string
     */
    protected $openGraphTitle;

    /**
     * @var string
     */
    protected $openGraphDescription;
    

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
    /**
     * @var string
     */
    private $condition;


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
     * Set condition
     *
     * @param string $condition
     * @return Content
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
     * @return \Enhavo\Bundle\MediaBundle\Entity\File 
     */
    public function getOpenGraphImage()
    {
        return $this->openGraphImage;
    }
}
