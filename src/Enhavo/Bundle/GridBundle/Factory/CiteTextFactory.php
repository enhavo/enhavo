<?php
/**
 * CiteTextFactory.php
 *
 * @since 02/11/16
 * @author gseidel
 */

namespace Enhavo\Bundle\GridBundle\Factory;

use Enhavo\Bundle\GridBundle\Entity\CiteText;
use Enhavo\Bundle\GridBundle\Item\ItemTypeInterface;

class CiteTextFactory extends AbstractItemFactory
{
    public function duplicate(ItemTypeInterface $original)
    {
        /** @var CiteText $data */
        /** @var CiteText $original */
        $data = new $this->dataClass;
        $data->setCite($original->getCite());
        return $data;
    }
}