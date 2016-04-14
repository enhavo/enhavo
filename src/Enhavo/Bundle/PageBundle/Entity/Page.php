<?php

namespace Enhavo\Bundle\PageBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Page
 */
class Page
{
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
    protected $teaser;

    /**
     * @var boolean
     */
    protected $social_media;

    /**
     * @var string
     */
    protected $slug;

    /**
     * @var string
     */
    protected $meta_description;

    /**
     * @var string
     */
    protected $page_title;

    /**
     * @var float
     */
    protected $priority;

    /**
     * @var string
     */
    protected $change_frequency;

    /**
     * @var boolean
     */
    protected $public;

    /**
     * @var \DateTime
     */
    protected $created;

    /**
     * @var \DateTime
     */
    protected $updated;

    /**
     * @var \Enhavo\Bundle\GridBundle\Entity\Content
     */
    protected $content;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    protected $picture;

    /**
     * @var \Enhavo\Bundle\AppBundle\Entity\Route
     */
    protected $route;

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
     * @param \Enhavo\Bundle\GridBundle\Entity\Content $content
     * @return Page
     */
    public function setContent(\Enhavo\Bundle\GridBundle\Entity\Content $content = null)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return \Enhavo\Bundle\GridBundle\Entity\Content
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
     * @return \Enhavo\Bundle\AppBundle\Entity\Route
     */
    public function getRoute()
    {
        return $this->route;
    }

    /**
     * @param \Enhavo\Bundle\AppBundle\Entity\Route $route
     */
    public function setRoute($route)
    {
        $this->route = $route;
    }
    /**
     * @var \Enhavo\Bundle\WorkflowBundle\Entity\WorkflowStatus
     */
    private $workflow_status;


    /**
     * Set workflowStatus
     *
     * @param \Enhavo\Bundle\WorkflowBundle\Entity\WorkflowStatus $workflowStatus
     *
     * @return Page
     */
    public function setWorkflowStatus(\Enhavo\Bundle\WorkflowBundle\Entity\WorkflowStatus $workflowStatus = null)
    {
        $this->workflow_status = $workflowStatus;

        return $this;
    }

    /**
     * Get workflowStatus
     *
     * @return \Enhavo\Bundle\WorkflowBundle\Entity\WorkflowStatus
     */
    public function getWorkflowStatus()
    {
        return $this->workflow_status;
    }
}
