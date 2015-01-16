<?php
/**
 * News.php
 *
 * @since 04/10/14
 * @author Gerhard Seidel <gseidel.message@googlemail.com>
 */

namespace esperanto\ProjectBundle\Entity;

use esperanto\NewsBundle\Entity\News as BaseNews;
use esperanto\SearchBundle\Search\SearchIndexInterface;
use esperanto\ContentBundle\Entity\Item;

class News extends BaseNews implements SearchIndexInterface
{
    /**
     * @var \esperanto\ContentBundle\Entity\Content
     */
    private $content;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $picture;

    /**
     * @var boolean
     */
    private $landing_page;

    /**
     * @var boolean
     */
    private $sticky;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->picture = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set content
     *
     * @param \esperanto\ContentBundle\Entity\Content $content
     * @return News
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
     * Add picture
     *
     * @param \esperanto\MediaBundle\Entity\File $picture
     * @return News
     */
    public function addPicture(\esperanto\MediaBundle\Entity\File $picture)
    {
        $this->picture[] = $picture;

        return $this;
    }

    /**
     * Remove picture
     *
     * @param \esperanto\MediaBundle\Entity\File $picture
     */
    public function removePicture(\esperanto\MediaBundle\Entity\File $picture)
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

    public function getIndexTitle()
    {
        return $this->getTitle();
    }

    public function getIndexTeaser()
    {
        return $this->getTeaser();
    }

    public function getIndexContent()
    {
        $content = array();
        if($this->getContent() && $this->getContent()->getItems()) {
            /** @var $item Item */
            foreach($this->getContent()->getItems() as $item) {
                if($item->getConfiguration()->getType() == 'text') {
                    $content[] = $this->getTitle();
                    $content[] = $this->getTeaser();
                    $content[] = html_entity_decode(strip_tags($item->getConfiguration()->getData()->getText()));
                    $content[] = $item->getConfiguration()->getData()->getTitle();
                }
            }
        }
        return implode("\n", $content);
    }

    public function getIndexRoute()
    {
        return 'esperanto_project_index';
    }

    public function getIndexRouteParameter()
    {
        return array(
            'id' => $this->getId(),
            'slug' => $this->getSlug()
        );
    }
    /**
     * Set landing_page
     *
     * @param boolean $landingPage
     * @return News
     */
    public function setLandingPage($landingPage)
    {
        $this->landing_page = $landingPage;

        return $this;
    }

    /**
     * Get landing_page
     *
     * @return boolean
     */
    public function getLandingPage()
    {
        if($this->landing_page === null) {
            return false;
        }

        return $this->landing_page;
    }

    /**
     * Set sticky
     *
     * @param boolean $sticky
     * @return News
     */
    public function setSticky($sticky)
    {
        $this->sticky = $sticky;

        return $this;
    }

    /**
     * Get sticky
     *
     * @return boolean
     */
    public function getSticky()
    {
        if($this->sticky === null) {
            return false;
        }

        return $this->sticky;
    }
}