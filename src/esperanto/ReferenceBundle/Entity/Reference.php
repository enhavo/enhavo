<?php

namespace esperanto\ReferenceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use BaconStringUtils\Slugifier;

/**
 * Reference
 */
class Reference
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
     * @var string
     */
    private $meta_description;

    /**
     * @var boolean
     */
    private $social_media;

    /**
     * @var boolean
     */
    private $public;

    /**
     * @var string
     */
    private $slug;

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
     * @var \DateTime
     */
    private $created;

    /**
     * @var \DateTime
     */
    private $updated;

    /**
     * @var integer
     */
    private $order;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $images;

    /**
     * @var \esperanto\ContentBundle\Entity\Content
     */
    private $content;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $preview_picture;

    /**
     * @var \esperanto\AdminBundle\Entity\Route
     */
    private $route;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->images = new \Doctrine\Common\Collections\ArrayCollection();
        $this->preview_picture = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return Reference
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
     * @return Reference
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
     * Set meta_description
     *
     * @param string $metaDescription
     * @return Reference
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
     * Set social_media
     *
     * @param boolean $socialMedia
     * @return Reference
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

    /**
     * Set public
     *
     * @param boolean $public
     * @return Reference
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
     * Set slug
     *
     * @param string $slug
     * @return Reference
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
                $this->slug = $slugifier->slugify($this->getTitle()).'.html';
            }
        }
        return $this->slug;
    }

    /**
     * Set page_title
     *
     * @param string $pageTitle
     * @return Reference
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
     * @return Reference
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
     * @return Reference
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
     * Set created
     *
     * @param \DateTime $created
     * @return Reference
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
     * @return Reference
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
     * Set order
     *
     * @param integer $order
     * @return Reference
     */
    public function setOrder($order)
    {
        $this->order = $order;

        return $this;
    }

    /**
     * Get order
     *
     * @return integer 
     */
    public function getOrder()
    {
        return $this->order;
    }


    /**
     * Add preview_picture
     *
     * @param \esperanto\MediaBundle\Entity\File $previewPicture
     * @return Reference
     */
    public function addPreviewPicture(\esperanto\MediaBundle\Entity\File $previewPicture)
    {
        $this->preview_picture[] = $previewPicture;

        return $this;
    }

    /**
     * Remove preview_picture
     *
     * @param \esperanto\MediaBundle\Entity\File $previewPicture
     */
    public function removePreviewPicture(\esperanto\MediaBundle\Entity\File $previewPicture)
    {
        $this->preview_picture->removeElement($previewPicture);
    }

    /**
     * Get preview_picture
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPreviewPicture()
    {
        return $this->preview_picture;
    }

    /**
     * Set content
     *
     * @param \esperanto\ContentBundle\Entity\Content $content
     * @return Reference
     */
    public function setContent(\esperanto\ContentBundle\Entity\Content $content = null)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return \esperanto\ContentBundle\Entity\Content
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Add images
     *
     * @param \esperanto\MediaBundle\Entity\File $images
     * @return Reference
     */
    public function addImage(\esperanto\MediaBundle\Entity\File $images)
    {
        $this->images[] = $images;

        return $this;
    }

    /**
     * Remove images
     *
     * @param \esperanto\MediaBundle\Entity\File $images
     */
    public function removeImage(\esperanto\MediaBundle\Entity\File $images)
    {
        $this->images->removeElement($images);
    }

    /**
     * Get images
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getImages()
    {
        return $this->images;
    }

    /**
     * @return \esperanto\AdminBundle\Entity\Route
     */
    public function getRoute()
    {
        return $this->route;
    }

    /**
     * @param \esperanto\AdminBundle\Entity\Route $route
     */
    public function setRoute($route)
    {
        $this->route = $route;
    }
}
