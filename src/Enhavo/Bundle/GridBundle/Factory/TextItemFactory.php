<?php
/**
 * TextFactory.php
 *
 * @since 02/11/16
 * @author gseidel
 */

namespace Enhavo\Bundle\GridBundle\Factory;

use Enhavo\Bundle\GridBundle\Model\Item\TextItem;
use Enhavo\Bundle\GridBundle\Model\ItemTypeInterface;

class TextItemFactory extends AbstractItemFactory
{
    public function duplicate(ItemTypeInterface $original)
    {
        /** @var TextItem $data */
        /** @var TextItem $original */
        $data = new $this->dataClass;

        $data->setTitle($original->getTitle());
        $data->setText($original->getText());

        return $data;
    }
}