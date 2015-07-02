<?php

namespace Enhavo\Bundle\ContentBundle\Entity;

use Enhavo\Bundle\ContentBundle\Item\ItemTypeInterface;

/**
 * TextText
 */
class TextText implements ItemTypeInterface
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
    private $textLeft;

    /**
     * @var string
     */
    private $titleLeft;

    /**
     * @var string
     */
    private $textRight;

    /**
     * @var string
     */
    private $titleRight;


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
     * Set textLeft
     *
     * @param string $textLeft
     *
     * @return TextText
     */
    public function setTextLeft($textLeft)
    {
        $this->textLeft = $textLeft;

        return $this;
    }

    /**
     * Get textLeft
     *
     * @return string
     */
    public function getTextLeft()
    {
        return $this->textLeft;
    }

    /**
     * Set titleLeft
     *
     * @param string $titleLeft
     *
     * @return TextText
     */
    public function setTitleLeft($titleLeft)
    {
        $this->titleLeft = $titleLeft;

        return $this;
    }

    /**
     * Get titleLeft
     *
     * @return string
     */
    public function getTitleLeft()
    {
        return $this->titleLeft;
    }

    /**
     * Set textRight
     *
     * @param string $textRight
     *
     * @return TextText
     */
    public function setTextRight($textRight)
    {
        $this->textRight = $textRight;

        return $this;
    }

    /**
     * Get textRight
     *
     * @return string
     */
    public function getTextRight()
    {
        return $this->textRight;
    }

    /**
     * Set titleRight
     *
     * @param string $titleRight
     *
     * @return TextText
     */
    public function setTitleRight($titleRight)
    {
        $this->titleRight = $titleRight;

        return $this;
    }

    /**
     * Get titleRight
     *
     * @return string
     */
    public function getTitleRight()
    {
        return $this->titleRight;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }
}
