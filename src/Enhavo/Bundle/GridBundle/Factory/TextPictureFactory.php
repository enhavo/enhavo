<?php
/**
 * TextPictureFactory.php
 *
 * @since 02/11/16
 * @author gseidel
 */

namespace Enhavo\Bundle\GridBundle\Factory;

use Enhavo\Bundle\GridBundle\Entity\TextPicture;
use Enhavo\Bundle\GridBundle\Item\ItemTypeInterface;

class TextPictureFactory extends AbstractItemFactory
{
    public function create()
    {
        /** @var TextPicture $data */
        $data = parent::create();
        $data->setLayout(TextPicture::LAYOUT_1_1);
        $data->setFloat(false);
        $data->setTextLeft(false);

        return $data;
    }

    public function duplicate(ItemTypeInterface $original)
    {
        /** @var TextPicture $data */
        /** @var TextPicture $original */
        $data = new $this->dataClass;

        $data->setTitle($original->getTitle());
        $data->setText($original->getText());
        $data->setTextLeft($original->getTextLeft());
        $data->setFloat($original->getFloat());
        $data->setCaption($original->getCaption());

        $newFile = $this->getFileFactory()->duplicate($original->getFile());
        $data->setFile($newFile);

        return $data;
    }
}