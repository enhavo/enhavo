<?php
/**
 * GalleryFactory.php
 *
 * @since 02/11/16
 * @author gseidel
 */

namespace Enhavo\Bundle\GridBundle\Factory;

use Enhavo\Bundle\GridBundle\Entity\Gallery;
use Enhavo\Bundle\GridBundle\Item\ItemTypeInterface;

class GalleryFactory extends AbstractItemFactory
{
    public function duplicate(ItemTypeInterface $original)
    {
        /** @var Gallery $data */
        /** @var Gallery $original */
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