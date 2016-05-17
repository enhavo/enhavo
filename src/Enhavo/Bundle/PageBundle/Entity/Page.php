<?php

namespace Enhavo\Bundle\PageBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Enhavo\Bundle\ContentBundle\Entity\Content;

/**
 * Page
 */
class Page extends Content
{
    /**
     * @var \Enhavo\Bundle\GridBundle\Entity\Grid
     */
    protected $grid;

    /**
     * Set content
     *
     * @param \Enhavo\Bundle\GridBundle\Entity\Grid $grid
     * @return Content
     */
    public function setGrid(\Enhavo\Bundle\GridBundle\Entity\Grid $grid = null)
    {
        $this->grid = $grid;

        return $this;
    }

    /**
     * Get content
     *
     * @return \Enhavo\Bundle\GridBundle\Entity\Grid
     */
    public function getGrid()
    {
        return $this->grid;
    }
}
