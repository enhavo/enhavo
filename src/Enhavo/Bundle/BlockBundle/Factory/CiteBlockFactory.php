<?php
/**
 * CiteTextFactory.php
 *
 * @since 02/11/16
 * @author gseidel
 */

namespace Enhavo\Bundle\BlockBundle\Factory;

use Enhavo\Bundle\BlockBundle\Model\Block\CiteBlock;
use Enhavo\Bundle\BlockBundle\Model\BlockInterface;

class CiteBlockFactory extends AbstractBlockFactory
{
    public function duplicate(BlockInterface $original)
    {
        /** @var CiteBlock $data */
        /** @var CiteBlock $original */
        $data = new $this->dataClass;
        $data->setText($original->getText());
        return $data;
    }
}
