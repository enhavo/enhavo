<?php
/**
 * GalleryFactory.php
 *
 * @since 02/11/16
 * @author gseidel
 */

namespace Enhavo\Bundle\GridBundle\Factory;

use Enhavo\Bundle\GridBundle\Model\Item\GalleryItem;
use Enhavo\Bundle\GridBundle\Model\ItemTypeInterface;

class GalleryItemFactory extends AbstractItemFactory
{
    public function duplicate(ItemTypeInterface $original)
    {
        /** @var GalleryItem $data */
        /** @var GalleryItem $original */
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