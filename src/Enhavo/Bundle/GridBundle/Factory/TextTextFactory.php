<?php
/**
 * TextTextFactory.php
 *
 * @since 02/11/16
 * @author gseidel
 */

namespace Enhavo\Bundle\GridBundle\Factory;

use Enhavo\Bundle\GridBundle\Entity\TextText;
use Enhavo\Bundle\GridBundle\Item\ItemTypeInterface;

class TextTextFactory extends AbstractItemFactory
{
    public function duplicate(ItemTypeInterface $original)
    {
        /** @var TextText $data */
        /** @var TextText $original */
        $data = new $this->dataClass;

        $data->setTitle($original->getTitle());
        $data->setTitleLeft($original->getTitleLeft());
        $data->setTextLeft($original->getTextLeft());
        $data->setTitleRight($original->getTitleRight());
        $data->setTextRight($original->getTextRight());
        $data->setLayout($original->getLayout());

        return $data;
    }
}