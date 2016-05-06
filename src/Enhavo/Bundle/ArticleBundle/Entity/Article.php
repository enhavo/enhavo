<?php
/**
 * Article.php
 *
 * @since 03/08/14
 * @author Gerhard Seidel <gseidel.message@googlemail.com>
 */

namespace Enhavo\Bundle\ArticleBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

class Article {

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
    protected $teaser;

    /**
     * @var \DateTime
     */
    protected $publication_date;

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
     * @var \Enhavo\Bundle\GridBundle\Entity\Content
     */
    protected $content;

    /**
     * @var \Enhavo\Bundle\MediaBundle\Entity\File
     */
    protected $picture;

    /**
     * @var \Enhavo\Bundle\AppBundle\Entity\Route
     */
    protected $route;

    /**
     * @var boolean
     */
    protected $social_media;

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
     * @return Article
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
     * @return Article
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
     * @return Article
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
     * @return Article
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
     * @return Article
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
     * Set social_media
     *
     * @param boolean $socialMedia
     * @return Article
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
     * @return Article
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
     * @return Article
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
     * @return Article
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
     * @return Article
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
     * @return Article
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
     * @param \Enhavo\Bundle\GridBundle\Entity\Content $content
     * @return Article
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
     * Set picture
     *
     * @param $picture \Enhavo\Bundle\MediaBundle\Entity\File|null
     * @return Article
     */
    public function setPicture($picture)
    {
        $this->picture = $picture;

        return $this;
    }

    /**
     * Get picture
     *
     * @return \Enhavo\Bundle\MediaBundle\Entity\File|null
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
}
