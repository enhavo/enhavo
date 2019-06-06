<?php
/**
 * TextPictureFactory.php
 *
 * @since 02/11/16
 * @author gseidel
 */

namespace Enhavo\Bundle\GridBundle\Factory;

use Enhavo\Bundle\GridBundle\Model\Item\TextPictureItem;
use Enhavo\Bundle\GridBundle\Model\ItemTypeInterface;

class TextPictureItemFactory extends AbstractItemFactory
{
    public function createNew()
    {
        /** @var TextPictureItem $data */
        $data = parent::createNew();
        $data->setLayout(TextPictureItem::LAYOUT_1_1);
        $data->setFloat(false);
        $data->setTextLeft(false);

        return $data;
    }

    public function duplicate(ItemTypeInterface $original)
    {
        /** @var TextPictureItem $data */
        /** @var TextPictureItem $original */
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