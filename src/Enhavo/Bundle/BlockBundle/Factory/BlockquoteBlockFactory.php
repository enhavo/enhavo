<?php
/**
 * BlockquoteTextFactory.php
 *
 * @since 02/11/16
 * @author gseidel
 */

namespace Enhavo\Bundle\BlockBundle\Factory;

use Enhavo\Bundle\BlockBundle\Model\Block\BlockquoteBlock;
use Enhavo\Bundle\BlockBundle\Model\BlockInterface;

class BlockquoteBlockFactory extends AbstractBlockFactory
{
    public function duplicate(BlockInterface $original)
    {
        /** @var BlockquoteBlock $data */
        /** @var BlockquoteBlock $original */
        $data = new $this->dataClass;
        $data->setText($original->getText());
        return $data;
    }
}
