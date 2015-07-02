<?php

namespace Enhavo\Bundle\PageBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use BaconStringUtils\Slugifier;

/**
 * Page
 */
class Page
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $teaser;

    /**
     * @var boolean
     */
    private $social_media;

    /**
     * @var string
     */
    private $slug;

    /**
     * @var string
     */
    private $meta_description;

    /**
     * @var string
     */
    private $page_title;

    /**
     * @var float
     */
    private $priority;

    /**
     * @var string
     */
    private $change_frequency;

    /**
     * @var boolean
     */
    private $public;

    /**
     * @var \DateTime
     */
    private $created;

    /**
     * @var \DateTime
     */
    private $updated;

    /**
     * @var \Enhavo\Bundle\ContentBundle\Entity\Content
     */
    private $content;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $picture;

    /**
     * @var \Enhavo\Bundle\AdminBundle\Entity\Route
     */
    private $route;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->picture = new \Doctrine\Common\Collections\ArrayCollection();
    }

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
     * @return Page
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
     * Set teaser
     *
     * @param string $teaser
     * @return Page
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
     * Set social_media
     *
     * @param boolean $socialMedia
     * @return Page
     */
    public function setSocialMedia($socialMedia)
    {
        $this->social_media = $socialMedia;

        return $this;
    }

    /**
     * Get social_media
     *
     * @return boolean 
     */
    public function getSocialMedia()
    {
        if($this->social_media === null) {
            return false;
        }

        return $this->social_media;
    }

    public function __toString()
    {
        if($this->getTitle()) {
            return $this->getTitle();
        }
        return '';
    }

    /**
     * Set slug
     *
     * @param string $slug
     * @return Page
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string 
     */
    public function getSlug()
    {
        if(empty($this->slug)) {
            $this->slug = '';
            if($this->getTitle()) {
                $slugifier = new Slugifier;
                $this->slug = $slugifier->slugify($this->getTitle());
            }
        }
        return $this->slug;
    }

    /**
     * Set meta_description
     *
     * @param string $metaDescription
     * @return Page
     */
    public function setMetaDescription($metaDescription)
    {
        $this->meta_description = $metaDescription;

        return $this;
    }

    /**
     * Get meta_description
     *
     * @return string 
     */
    public function getMetaDescription()
    {
        return $this->meta_description;
    }

    /**
     * Set page_title
     *
     * @param string $pageTitle
     * @return Page
     */
    public function setPageTitle($pageTitle)
    {
        $this->page_title = $pageTitle;

        return $this;
    }

    /**
     * Get page_title
     *
     * @return string 
     */
    public function getPageTitle()
    {
        return $this->page_title;
    }

    /**
     * Set priority
     *
     * @param float $priority
     * @return Page
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
     * @return Page
     */
    public function setChangeFrequency($changeFrequency)
    {
        $this->change_frequency = $changeFrequency;

        return $this;
    }

    /**
     * Get change_frequency
     *
     * @return string 
     */
    public function getChangeFrequency()
    {
        if($this->change_frequency === null) {
            return 'never';
        }

        return $this->change_frequency;
    }

    /**
     * Set public
     *
     * @param boolean $public
     * @return Page
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
     * Set created
     *
     * @param \DateTime $created
     * @return Page
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
     * @return Page
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
     * @ORM\PrePersist
     */
    public function prePersist()
    {
        $date = new \DateTime();
        $this->setCreated($date);
        $this->setUpdated($date);
    }

    /**
     * @ORM\PreUpdate
     */
    public function preUpdate()
    {
        $this->setUpdated(new \DateTime());
    }

    /**
     * Set content
     *
     * @param \Enhavo\Bundle\ContentBundle\Entity\Content $content
     * @return Page
     */
    public function setContent(\Enhavo\Bundle\ContentBundle\Entity\Content $content = null)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return \Enhavo\Bundle\ContentBundle\Entity\Content
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Add picture
     *
     * @param \Enhavo\Bundle\MediaBundle\Entity\File $picture
     * @return Page
     */
    public function addPicture(\Enhavo\Bundle\MediaBundle\Entity\File $picture)
    {
        $this->picture[] = $picture;

        return $this;
    }

    /**
     * Remove picture
     *
     * @param \Enhavo\Bundle\MediaBundle\Entity\File $picture
     */
    public function removePicture(\Enhavo\Bundle\MediaBundle\Entity\File $picture)
    {
        $this->picture->removeElement($picture);
    }

    /**
     * Get picture
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPicture()
    {
        return $this->picture;
    }

    /**
     * @return \Enhavo\Bundle\AdminBundle\Entity\Route
     */
    public function getRoute()
    {
        return $this->route;
    }

    /**
     * @param \Enhavo\Bundle\AdminBundle\Entity\Route $route
     */
    public function setRoute($route)
    {
        $this->route = $route;
    }
}
