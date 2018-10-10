<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 10.10.18
 * Time: 15:16
 */

namespace Enhavo\Bundle\GridBundle\Model\Column;

use Enhavo\Bundle\GridBundle\Entity\AbstractItem;
use Enhavo\Bundle\GridBundle\Model\GridsAwareInterface;

abstract class Column extends AbstractItem implements GridsAwareInterface
{
    const WIDTH_FULL = 'full';
    const WIDTH_CONTAINER = 'container';

    /**
     * @var string
     */
    private $width = self::WIDTH_CONTAINER;

    /**
     * @var string
     */
    private $style;

    /**
     * @return string
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * @param string $width
     */
    public function setWidth($width)
    {
        $this->width = $width;
    }

    /**
     * @return string
     */
    public function getStyle()
    {
        return $this->style;
    }

    /**
     * @param string $style
     */
    public function setStyle($style)
    {
        $this->style = $style;
    }
}