<?php

namespace Enhavo\Bundle\GridBundle\Model\Item;

use Enhavo\Bundle\GridBundle\Entity\AbstractItem;

/**
 * CiteText
 */
class CiteItem extends AbstractItem
{
    /**
     * @var string
     */
    private $text;

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
