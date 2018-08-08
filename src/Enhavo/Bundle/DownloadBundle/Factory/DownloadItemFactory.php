<?php
/**
 * GalleryFactory.php
 *
 * @since 02/11/16
 * @author gseidel
 */

namespace Enhavo\Bundle\DownloadBundle\Factory;

use Enhavo\Bundle\DownloadBundle\Entity\DownloadItem;
use Enhavo\Bundle\GridBundle\Factory\AbstractItemFactory;
use Enhavo\Bundle\GridBundle\Model\ItemTypeInterface;

class DownloadItemFactory extends AbstractItemFactory
{
    public function duplicate(ItemTypeInterface $original)
    {
        /** @var DownloadItem $data */
        /** @var DownloadItem $original */
        $data = new $this->dataClass;
        return $data;
    }
}