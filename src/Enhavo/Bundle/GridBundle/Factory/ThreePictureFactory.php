<?php
/**
 * ThreePictureFactory.php
 *
 * @since 02/11/16
 * @author gseidel
 */

namespace Enhavo\Bundle\GridBundle\Factory;

use Enhavo\Bundle\GridBundle\Entity\ThreePicture;
use Enhavo\Bundle\GridBundle\Item\ItemTypeInterface;

class ThreePictureFactory extends AbstractItemFactory
{
    public function duplicate(ItemTypeInterface $original)
    {
        /** @var ThreePicture $data */
        /** @var ThreePicture $original */
        $data = new $this->dataClass;

        $data->setTitleLeft($original->getTitleLeft());
        $data->setCaptionLeft($original->getCaptionLeft());
        $data->setTitleCenter($original->getTitleCenter());
        $data->setCaptionCenter($original->getCaptionCenter());
        $data->setTitleRight($original->getTitleRight());
        $data->setCaptionRight($original->getCaptionRight());

        $newFile = $this->getFileFactory()->duplicate($original->getFileLeft());
        $data->setFileLeft($newFile);

        $newFile = $this->getFileFactory()->duplicate($original->getFileCenter());
        $data->setFileCenter($newFile);

        $newFile = $this->getFileFactory()->duplicate($original->getFileRight());
        $data->setFileRight($newFile);

        return $data;
    }
}