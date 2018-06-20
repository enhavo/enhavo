<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 20.06.18
 * Time: 11:34
 */

namespace Enhavo\Bundle\PageBundle\Model;

use Doctrine\Common\Collections\Collection;
use Enhavo\Bundle\GridBundle\Model\GridInterface;

interface PageInterface
{
    /**
     * Set content
     *
     * @param GridInterface $grid
     * @return PageInterface
     */
    public function setGrid(GridInterface $grid = null);

    /**
     * Get content
     *
     * @return GridInterface
     */
    public function getGrid();

    /**
     * Set code
     *
     * @param string $code
     * @return PageInterface
     */
    public function setCode($code);

    /**
     * Get code
     *
     * @return string
     */
    public function getCode();

    /**
     * @return PageInterface
     */
    public function getParent();

    /**
     * @param PageInterface $parent
     */
    public function setParent($parent);

    /**
     * @param PageInterface $page
     * @return PageInterface
     */
    public function addChild(PageInterface $page);

    /**
     * @return Collection
     */
    public function getChildren();

    /**
     * @param PageInterface $page
     * @return PageInterface
     */
    public function removeChild(PageInterface $page);
}