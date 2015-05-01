<?php
/**
 * Slider.php
 *
 * @since 15/10/14
 * @author Gerhard Seidel <gseidel.message@googlemail.com>
 */

namespace esperanto\ProjectBundle\Entity;

use esperanto\SliderBundle\Entity\Slide as BaseSlide;
use esperanto\ProjectBundle\Entity\Slider;

class Slide extends BaseSlide
{
    /**
     * @var string
     */
    private $link;

    /**
     * @var \esperanto\PageBundle\Entity\Page
     */
    private $page;

    /**
     * @var \esperanto\ReferenceBundle\Entity\Reference
     */
    private $reference;

    /**
     * @var \esperanto\NewsBundle\Entity\News
     */
    private $news;

    /**
     * Set link
     *
     * @param string $link
     * @return Slider
     */
    public function setLink($link)
    {
        $this->link = $link;

        return $this;
    }

    /**
     * Get link
     *
     * @return string
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * Set page
     *
     * @param \esperanto\PageBundle\Entity\Page $page
     * @return Slider
     */
    public function setPage(\esperanto\PageBundle\Entity\Page $page = null)
    {
        $this->page = $page;

        return $this;
    }

    /**
     * Get page
     *
     * @return \esperanto\PageBundle\Entity\Page
     */
    public function getPage()
    {
        return $this->page;
    }

    public function getLinkData()
    {
        switch($this->getLinkType()) {
            case('reference'):
                return $this->getReference() ? $this->getReference()->getId() : null;
            case('external'):
                return $this->getUrl();
            case('news'):
                return $this->getNews() ? $this->getNews()->getId() : null;
            case('page'):
                return $this->getPage() ? $this->getPage()->getId() : null;
        }
        return null;
    }

    /**
     * Set news
     *
     * @param \esperanto\NewsBundle\Entity\News $news
     * @return Slider
     */
    public function setNews(\esperanto\NewsBundle\Entity\News $news = null)
    {
        $this->news = $news;

        return $this;
    }

    /**
     * Get news
     *
     * @return \esperanto\NewsBundle\Entity\News
     */
    public function getNews()
    {
        return $this->news;
    }

    /**
     * Set reference
     *
     * @param \esperanto\ReferenceBundle\Entity\Reference $reference
     * @return Slider
     */
    public function setReference(\esperanto\ReferenceBundle\Entity\Reference $reference = null)
    {
        $this->reference = $reference;

        return $this;
    }

    /**
     * Get reference
     *
     * @return \esperanto\ReferenceBundle\Entity\Reference
     */
    public function getReference()
    {
        return $this->reference;
    }


    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $image;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->image = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add image
     *
     * @param \esperanto\MediaBundle\Entity\File $image
     * @return Slide
     */
    public function addImage(\esperanto\MediaBundle\Entity\File $image)
    {
        $this->image[] = $image;

        return $this;
    }

    /**
     * Remove image
     *
     * @param \esperanto\MediaBundle\Entity\File $image
     */
    public function removeImage(\esperanto\MediaBundle\Entity\File $image)
    {
        $this->image->removeElement($image);
    }

    /**
     * Get image
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getImage()
    {
        return $this->image;
    }
}
