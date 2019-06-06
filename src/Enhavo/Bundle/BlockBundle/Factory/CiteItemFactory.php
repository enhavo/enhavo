<?php
/**
 * CiteTextFactory.php
 *
 * @since 02/11/16
 * @author gseidel
 */

namespace Enhavo\Bundle\GridBundle\Factory;

use Enhavo\Bundle\GridBundle\Model\Item\CiteItem;
use Enhavo\Bundle\GridBundle\Model\ItemTypeInterface;

class CiteItemFactory extends AbstractItemFactory
{
    public function duplicate(ItemTypeInterface $original)
    {
        /** @var CiteItem $data */
        /** @var CiteItem $original */
        $data = new $this->dataClass;
        $data->setText($original->getText());
        return $data;
    }
}