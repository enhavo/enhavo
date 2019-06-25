<?php
/**
 * Created by PhpStorm.
 * User: philippsester
 * Date: 30.05.19
 * Time: 16:29
 */

namespace Enhavo\Bundle\SidebarBundle\Entity;

use Enhavo\Bundle\BlockBundle\Model\Column\Column;
use Enhavo\Bundle\BlockBundle\Model\NodeInterface;

class SidebarColumnBlock extends Column
{
    /**
     * @var Sidebar
     */
    private $sidebar;

    /**
     * @var NodeInterface
     */
    private $column;

    /**
     * @return NodeInterface
     */
    public function getColumn()
    {
        return $this->column;
    }

    /**
     * @param NodeInterface $column
     */
    public function setColumn($column)
    {
        $column->setParent($this->getNode());
        $column->setProperty('column');
        $column->setType(NodeInterface::TYPE_LIST);
        $this->column = $column;
    }

    public function setNode(NodeInterface $node = null)
    {
        parent::setNode($node);
        if($this->getColumn()) {
            $this->getColumn()->setParent($node);
        }
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
}
