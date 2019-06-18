<?php

namespace Enhavo\Bundle\BlockBundle\Model\Block;

use Enhavo\Bundle\BlockBundle\Entity\AbstractBlock;

/**
 * Text
 */
class TextBlock extends AbstractBlock
{
    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $text;

    /**
     * Set title
     *
     * @param string $title
     * @return TextBlock
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
     * Set text
     *
     * @param string $text
     * @return TextBlock
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Get text
     *
     * @return string 
     */
    public function getText()
    {
        return $this->text;
    }
}
