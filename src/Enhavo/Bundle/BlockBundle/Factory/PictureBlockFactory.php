<?php
/**
 * PictureFactory.php
 *
 * @since 02/11/16
 * @author gseidel
 */

namespace Enhavo\Bundle\BlockBundle\Factory;

use Enhavo\Bundle\BlockBundle\Model\Block\PictureBlock;
use Enhavo\Bundle\BlockBundle\Model\BlockInterface;

class PictureBlockFactory extends AbstractBlockFactory
{
    public function duplicate(BlockInterface $original)
    {
        /** @var PictureBlock $data */
        /** @var PictureBlock $original */
        $data = new $this->dataClass;

        $data->setTitle($original->getTitle());
        $data->setCaption($original->getCaption());

        $newFile = $this->getFileFactory()->duplicate($original->getFile());
        $data->setFile($newFile);

        return $data;
    }
}
