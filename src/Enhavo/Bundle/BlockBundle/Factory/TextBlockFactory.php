<?php
/**
 * TextFactory.php
 *
 * @since 02/11/16
 * @author gseidel
 */

namespace Enhavo\Bundle\BlockBundle\Factory;

use Enhavo\Bundle\BlockBundle\Model\Block\TextBlock;
use Enhavo\Bundle\BlockBundle\Model\BlockTypeInterface;

class TextBlockFactory extends AbstractBlockFactory
{
    public function duplicate(BlockTypeInterface $original)
    {
        /** @var TextBlock $data */
        /** @var TextBlock $original */
        $data = new $this->dataClass;

        $data->setTitle($original->getTitle());
        $data->setText($original->getText());

        return $data;
    }
}