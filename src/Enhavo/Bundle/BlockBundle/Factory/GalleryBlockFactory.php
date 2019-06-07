<?php
/**
 * GalleryFactory.php
 *
 * @since 02/11/16
 * @author gseidel
 */

namespace Enhavo\Bundle\BlockBundle\Factory;

use Enhavo\Bundle\BlockBundle\Model\Block\GalleryBlock;
use Enhavo\Bundle\BlockBundle\Model\BlockTypeInterface;

class GalleryBlockFactory extends AbstractBlockFactory
{
    public function duplicate(BlockTypeInterface $original)
    {
        /** @var GalleryBlock $data */
        /** @var GalleryBlock $original */
        $data = new $this->dataClass;

        $data->setTitle($original->getTitle());
        $data->setText($original->getText());

        foreach($original->getFiles() as $file) {
            $newFile = $this->getFileFactory()->duplicate($file);
            $data->addFile($newFile);
        }
        
        return $data;
    }
}