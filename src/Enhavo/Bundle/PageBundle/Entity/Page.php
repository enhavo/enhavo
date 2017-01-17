<?php

namespace Enhavo\Bundle\PageBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Enhavo\Bundle\ContentBundle\Entity\Content;
use Enhavo\Bundle\GridBundle\Model\GridInterface;

/**
 * Page
 */
class Page extends Content
{
    /**
     * @var GridInterface
     */
    protected $grid;

    /**
     * @var string
     */
    private $code;

    /**
     * Set content
     *
     * @param GridInterface $grid
     * @return Content
     */
    public function setGrid(GridInterface $grid = null)
    {
        $this->grid = $grid;

        return $this;
    }

    /**
     * Get content
     *
     * @return GridInterface
     */
    public function getGrid()
    {
        return $this->grid;
    }
    
    /**
     * Set code
     *
     * @param string $code
     * @return Page
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code
     *
     * @return string 
     */
    public function getCode()
    {
        return $this->code;
    }
}
