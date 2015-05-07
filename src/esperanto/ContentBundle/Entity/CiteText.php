<?php

namespace esperanto\ContentBundle\Entity;

use esperanto\ContentBundle\Item\ItemTypeInterface;

/**
 * CiteText
 */
class CiteText implements ItemTypeInterface
{
    /**
     * @var integer
     */
    private $id;

    private $text;

    private $title;

    private $cite;

    private $textleft;

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
     * @return mixed
     */
    public function getTextleft()
    {
        return $this->textleft;
    }

    /**
     * @param mixed $textLeft
     */
    public function setTextleft($textleft)
    {
        $this->textleft = $textleft;
    }

    /**
     * @return mixed
     */
    public function getCite()
    {
        return $this->cite;
    }

    /**
     * @param mixed $cite
     */
    public function setCite($cite)
    {
        $this->cite = $cite;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return mixed
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param mixed $text
     */
    public function setText($text)
    {
        $this->text = $text;
    }
}

