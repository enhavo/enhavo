<?php
/**
 * TextPictureFactory.php
 *
 * @since 02/11/16
 * @author gseidel
 */

namespace Enhavo\Bundle\BlockBundle\Factory;

use Enhavo\Bundle\BlockBundle\Model\Block\TextPictureBlock;
use Enhavo\Bundle\BlockBundle\Model\BlockTypeInterface;

class TextPictureBlockFactory extends AbstractBlockFactory
{
    public function createNew()
    {
        /** @var TextPictureBlock $data */
        $data = parent::createNew();
        $data->setLayout(TextPictureBlock::LAYOUT_1_1);
        $data->setFloat(false);
        $data->setTextLeft(false);

        return $data;
    }

    public function duplicate(BlockTypeInterface $original)
    {
        /** @var TextPictureBlock $data */
        /** @var TextPictureBlock $original */
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