<?php
/**
 * VideoFactory.php
 *
 * @since 02/11/16
 * @author gseidel
 */

namespace Enhavo\Bundle\GridBundle\Factory;

use Enhavo\Bundle\GridBundle\Entity\Video;
use Enhavo\Bundle\GridBundle\Item\ItemTypeInterface;

class VideoFactory extends AbstractItemFactory
{
    public function duplicate(ItemTypeInterface $original)
    {
        /** @var Video $data */
        /** @var Video $original */
        $data = new $this->dataClass;

        $data->setTitle($original->getTitle());
        $data->setUrl($original->getUrl());

        return $data;
    }
}