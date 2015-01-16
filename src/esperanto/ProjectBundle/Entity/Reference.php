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
     * @var \Doctrine\Common\Collections\Collection
     */
    private $sectors;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $styles;

    /**
     * @var \esperanto\ContentBundle\Entity\Content
     */
    private $content;

    /**
     * @var string
     */
    private $detail_title;

    /**
     * @var string
     */
    private $link;

    /**
     * @var boolean
     */
    private $landing_page;

    /**
     * @var string
     */
    private $style;

    /**
     * @var string
     */
    private $channel;

    /**
     * @var string
     */
    private $target_group;

    /**
     * @var string
     */
    private $client;

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
        return 'how_to_video_main_reference_show';
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
     * Add sectors
     *
     * @param \esperanto\CategoryBundle\Entity\Category $sectors
     * @return Reference
     */
    public function addSector(\esperanto\CategoryBundle\Entity\Category $sectors)
    {
        $this->sectors[] = $sectors;

        return $this;
    }

    /**
     * Remove sectors
     *
     * @param \esperanto\CategoryBundle\Entity\Category $sectors
     */
    public function removeSector(\esperanto\CategoryBundle\Entity\Category $sectors)
    {
        $this->sectors->removeElement($sectors);
    }

    /**
     * Get sectors
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSectors()
    {
        return $this->sectors;
    }

    /**
     * Add styles
     *
     * @param \esperanto\CategoryBundle\Entity\Category $styles
     * @return Reference
     */
    public function addStyle(\esperanto\CategoryBundle\Entity\Category $styles)
    {
        $this->styles[] = $styles;

        return $this;
    }

    /**
     * Remove styles
     *
     * @param \esperanto\CategoryBundle\Entity\Category $styles
     */
    public function removeStyle(\esperanto\CategoryBundle\Entity\Category $styles)
    {
        $this->styles->removeElement($styles);
    }

    /**
     * Set style
     *
     * @param string $style
     * @return Reference
     */
    public function setStyle($style)
    {
        $this->style = $style;

        return $this;
    }

    /**
     * Get style
     *
     * @return string
     */
    public function getStyle()
    {
        return $this->style;
    }

    /**
     * Set channel
     *
     * @param string $channel
     * @return Reference
     */
    public function setChannel($channel)
    {
        $this->channel = $channel;

        return $this;
    }

    /**
     * Get channel
     *
     * @return string
     */
    public function getChannel()
    {
        return $this->channel;
    }

    /**
     * Set target_group
     *
     * @param string $targetGroup
     * @return Reference
     */
    public function setTargetGroup($targetGroup)
    {
        $this->target_group = $targetGroup;

        return $this;
    }

    /**
     * Get target_group
     *
     * @return string
     */
    public function getTargetGroup()
    {
        return $this->target_group;
    }

    /**
     * Set client
     *
     * @param string $client
     * @return Reference
     */
    public function setClient($client)
    {
        $this->client = $client;

        return $this;
    }

    /**
     * Get client
     *
     * @return string
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * Set detail_title
     *
     * @param string $detailTitle
     * @return Reference
     */
    public function setDetailTitle($detailTitle)
    {
        $this->detail_title = $detailTitle;

        return $this;
    }

    /**
     * Get detail_title
     *
     * @return string
     */
    public function getDetailTitle()
    {
        return $this->detail_title;
    }

    /**
     * Set link
     *
     * @param string $link
     * @return Reference
     */
    public function setLink($link)
    {
        $url = parse_url($link);
        if(is_array($url) && array_key_exists('host', $url) && array_key_exists('path', $url)) {
            $url = sprintf('//%s%s', $url['host'], $url['path']);
            $this->link = $url;
        } else {
            $this->link = $link;
        }

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
     * Get styles
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getStyles()
    {
        return $this->styles;
    }

    public function getVideoId()
    {
        $matches = array();
        preg_match('#//www.edge-cdn.net/video_([0-9]+)#', $this->getLink(), $matches);
        if(count($matches)) {
            return $matches[1];
        }
        return null;
    }

    /**
     * Set landing_page
     *
     * @param boolean $landingPage
     * @return Reference
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