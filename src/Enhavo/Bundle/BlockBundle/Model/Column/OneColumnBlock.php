<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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

    public function setNode(?NodeInterface $node = null)
    {
        parent::setNode($node);
        if ($this->getColumn()) {
            $this->getColumn()->setParent($node);
        }
    }
}
