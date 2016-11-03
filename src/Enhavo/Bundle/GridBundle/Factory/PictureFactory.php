<?php
/**
 * PictureFactory.php
 *
 * @since 02/11/16
 * @author gseidel
 */

namespace Enhavo\Bundle\GridBundle\Factory;

use Enhavo\Bundle\GridBundle\Entity\Picture;
use Enhavo\Bundle\GridBundle\Item\ItemTypeInterface;

class PictureFactory extends AbstractItemFactory
{
    public function duplicate(ItemTypeInterface $original)
    {
        /** @var Picture $data */
        /** @var Picture $original */
        $data = new $this->dataClass;

        $data->setTitle($original->getTitle());
        $data->setCaption($original->getCaption());

        $newFile = $this->getFileFactory()->duplicate($original->getFile());
        $data->setFile($newFile);

        return $data;
    }
}