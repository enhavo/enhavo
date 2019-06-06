<?php
/**
 * PictureFactory.php
 *
 * @since 02/11/16
 * @author gseidel
 */

namespace Enhavo\Bundle\GridBundle\Factory;

use Enhavo\Bundle\GridBundle\Model\Item\PictureItem;
use Enhavo\Bundle\GridBundle\Model\ItemTypeInterface;

class PictureItemFactory extends AbstractItemFactory
{
    public function duplicate(ItemTypeInterface $original)
    {
        /** @var PictureItem $data */
        /** @var PictureItem $original */
        $data = new $this->dataClass;

        $data->setTitle($original->getTitle());
        $data->setCaption($original->getCaption());

        $newFile = $this->getFileFactory()->duplicate($original->getFile());
        $data->setFile($newFile);

        return $data;
    }
}