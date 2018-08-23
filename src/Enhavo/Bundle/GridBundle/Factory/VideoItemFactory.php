<?php
/**
 * VideoFactory.php
 *
 * @since 02/11/16
 * @author gseidel
 */

namespace Enhavo\Bundle\GridBundle\Factory;

use Enhavo\Bundle\GridBundle\Model\Item\VideoItem;
use Enhavo\Bundle\GridBundle\Model\ItemTypeInterface;

class VideoItemFactory extends AbstractItemFactory
{
    public function duplicate(ItemTypeInterface $original)
    {
        /** @var VideoItem $data */
        /** @var VideoItem $original */
        $data = new $this->dataClass;

        $data->setTitle($original->getTitle());
        $data->setUrl($original->getUrl());

        return $data;
    }
}