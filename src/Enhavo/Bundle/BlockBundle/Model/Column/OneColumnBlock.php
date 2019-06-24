<?php

namespace Enhavo\Bundle\BlockBundle\Model\Column;

use Enhavo\Bundle\BlockBundle\Model\NodeInterface;

class OneColumnBlock extends Column
{
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
}
