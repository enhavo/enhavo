<?php
/**
 * PicturePictureFactory.php
 *
 * @since 02/11/16
 * @author gseidel
 */

namespace Enhavo\Bundle\GridBundle\Factory;

use Enhavo\Bundle\GridBundle\Entity\PicturePicture;
use Enhavo\Bundle\GridBundle\Item\ItemTypeInterface;

class PicturePictureFactory extends AbstractItemFactory
{
    public function duplicate(ItemTypeInterface $original)
    {
        /** @var PicturePicture $data */
        /** @var PicturePicture $original */
        $data = new $this->dataClass;

        $data->setTitle($original->getTitle());
        $data->setCaptionLeft($original->getCaptionLeft());
        $data->setCaptionRight($original->getCaptionRight());

        $newFile = $this->getFileFactory()->duplicate($original->getFileLeft());
        $data->setFileLeft($newFile);

        $newFile = $this->getFileFactory()->duplicate($original->getFileRight());
        $data->setFileRight($newFile);

        return $data;
    }
}