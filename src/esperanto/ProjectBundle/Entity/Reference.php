<?php
/**
 * Reference.php
 *
 * @since 04/10/14
 * @author Gerhard Seidel <gseidel.message@googlemail.com>
 */

namespace esperanto\ProjectBundle\Entity;

use esperanto\ReferenceBundle\Entity\Reference as BaseReference;
use esperanto\SearchBundle\Search\SearchIndexInterface;
use esperanto\ContentBundle\Entity\Item;

class Reference extends BaseReference implements SearchIndexInterface
{
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
     * Constructor
     */
    public function __construct()
    {
        $this->images = new \Doctrine\Common\Collections\ArrayCollection();
        $this->preview_picture = new \Doctrine\Common\Collections\ArrayCollection();
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
}