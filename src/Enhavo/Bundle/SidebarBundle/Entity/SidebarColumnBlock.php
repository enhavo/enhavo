<?php
/**
 * Created by PhpStorm.
 * User: philippsester
 * Date: 30.05.19
 * Time: 16:29
 */

namespace Enhavo\Bundle\SidebarBundle\Entity;

use Enhavo\Bundle\BlockBundle\Model\Column\Column;
use Enhavo\Bundle\BlockBundle\Model\ContainerInterface;

class SidebarColumnBlock extends Column
{
    /**
     * @var Sidebar
     */
    private $sidebar;

    /**
     * @var ContainerInterface
     */
    private $column;

    /**
     * @return ContainerInterface
     */
    public function getColumn()
    {
        return $this->column;
    }

    /**
     * @param ContainerInterface $column
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

    public function getContainers()
    {
        $containers = [];
        if($this->column) {
            $containers[] = $this->column;
        }
        return $containers;
    }
}
