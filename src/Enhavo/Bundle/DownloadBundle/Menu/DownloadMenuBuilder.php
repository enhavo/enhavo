<?php
/**
 * DownloadMenuBuilder.php
 *
 * @since 21/09/16
 * @author gseidel
 */

namespace Enhavo\Bundle\DownloadBundle\Menu;


use Enhavo\Bundle\AppBundle\Menu\Builder\BaseMenuBuilder;

class DownloadMenuBuilder extends BaseMenuBuilder
{
    public function createMenu(array $options)
    {
        $this->setOption('icon', $options, 'download-1');
        $this->setOption('label', $options, 'download.label.download');
        $this->setOption('translationDomain', $options, 'EnhavoDownloadBundle');
        $this->setOption('route', $options, 'enhavo_download_download_index');
        $this->setOption('role', $options, 'ROLE_ENHAVO_DOWNLOAD_DOWNLOAD_INDEX');
        return parent::createMenu($options);
    }

    public function getType()
    {
        return 'download';
    }
}