<?php
/**
 * News.php
 *
 * @since 03/08/14
 * @author Gerhard Seidel <gseidel.message@googlemail.com>
 */

namespace enhavo\NewsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use BaconStringUtils\Slugifier;

abstract class News {

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
     * @var \DateTime
     */
    private $publication_date;

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
     * @var \enhavo\ContentBundle\Entity\Content
     */
    private $content;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $picture;

    /**
     * @var \enhavo\AdminBundle\Entity\Route
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
     * Set created
     *
     * @param \DateTime $created
     * @return News
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
     * @return News
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
     * Set title
     *
     * @param string $title
     * @return News
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
     * @return News
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
     * Set publication_date
     *
     * @param \DateTime $publicationDate
     * @return News
     */
    public function setPublicationDate($publicationDate)
    {
        $this->publication_date = $publicationDate;

        return $this;
    }

    /**
     * Get publication_date
     *
     * @return \DateTime 
     */
    public function getPublicationDate()
    {
        return $this->publication_date;
    }

    /**
     * @var boolean
     */
    private $social_media;


    /**
     * Set social_media
     *
     * @param boolean $socialMedia
     * @return News
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
     * Set slug
     *
     * @param string $slug
     * @return News
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
     * Set meta_description
     *
     * @param string $metaDescription
     * @return News
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
     * @return News
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
     * @return News
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
     * @return News
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
     * @return News
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
     * Set content
     *
     * @param \enhavo\ContentBundle\Entity\Content $content
     * @return News
     */
    public function setContent(\enhavo\ContentBundle\Entity\Content $content = null)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return \enhavo\ContentBundle\Entity\Content
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Add picture
     *
     * @param \enhavo\MediaBundle\Entity\File $picture
     * @return News
     */
    public function addPicture(\enhavo\MediaBundle\Entity\File $picture)
    {
        $this->picture[] = $picture;

        return $this;
    }

    /**
     * Remove picture
     *
     * @param \enhavo\MediaBundle\Entity\File $picture
     */
    public function removePicture(\enhavo\MediaBundle\Entity\File $picture)
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
     * @return \enhavo\AdminBundle\Entity\Route
     */
    public function getRoute()
    {
        return $this->route;
    }

    /**
     * @param \enhavo\AdminBundle\Entity\Route $route
     */
    public function setRoute($route)
    {
        $this->route = $route;
    }
}
