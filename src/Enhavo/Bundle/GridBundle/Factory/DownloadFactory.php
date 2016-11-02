<?php
/**
 * DownloadFactory.php
 *
 * @since 02/11/16
 * @author gseidel
 */

namespace Enhavo\Bundle\GridBundle\Factory;

use Enhavo\Bundle\DownloadBundle\Entity\DownloadItem;
use Enhavo\Bundle\GridBundle\Item\ItemTypeInterface;

class DownloadFactory extends AbstractItemFactory
{
    public function duplicate(ItemTypeInterface $original)
    {
        /** @var DownloadItem $data */
        /** @var DownloadItem $original */
        $data = new $this->dataClass;

        $data->setTitle($original->getTitle());
        $data->setDownload($original->getDownload());
        if($original->getFile()) {
            $newFile = $this->getFileFactory()->duplicate($original->getFile());
            $data->setFile($newFile);
        }

        return $data;
    }
}