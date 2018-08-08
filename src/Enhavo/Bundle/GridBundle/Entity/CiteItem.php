<?php

namespace Enhavo\Bundle\GridBundle\Entity;


/**
 * CiteText
 */
class CiteItem extends AbstractItem
{
    /**
     * @var string
     */
    protected $text;

    /**
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param string $text
     */
    public function setText($text)
    {
        $this->text = $text;
    }
}
