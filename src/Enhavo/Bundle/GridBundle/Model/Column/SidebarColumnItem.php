<?php
/**
 * Created by PhpStorm.
 * User: philippsester
 * Date: 30.05.19
 * Time: 16:29
 */

namespace Enhavo\Bundle\GridBundle\Model\Column;

use Enhavo\Bundle\GridBundle\Entity\AbstractItem;
use Enhavo\Bundle\GridBundle\Entity\Grid;
use Enhavo\Bundle\GridBundle\Model\GridsAwareInterface;
use Enhavo\Bundle\SidebarBundle\Entity\Sidebar;
use Enhavo\Bundle\GridBundle\Model\GridInterface;

class SidebarColumnItem extends Column
{

    /**
     * @var Sidebar
     */
    private $sidebar;

    /**
     * @var GridInterface
     */
    private $column;

    /**
     * @return GridInterface
     */
    public function getColumn()
    {
        return $this->column;
    }

    /**
     * @param GridInterface $column
     */
    public function setColumn($column)
    {
        $this->column = $column;
    }

    /**
     * @return Sidebar
     */
    public function getSidebar()
    {
        return $this->sidebar;
    }

    /**
     * @param Sidebar $sidebar
     */
    public function setSidebar(Sidebar $sidebar)
    {
        $this->sidebar = $sidebar;
    }

    public function getGrids()
    {
        $grids = [];
        if($this->column) {
            $grids[] = $this->column;
        }
        return $grids;
    }
}
