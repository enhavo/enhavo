<?php
/**
 * DownloadMenu.php
 *
 * @since 21/09/16
 * @author gseidel
 */

namespace Enhavo\Bundle\DownloadBundle\Menu;

use Enhavo\Bundle\AppBundle\Menu\Menu\BaseMenu;

class DownloadMenu extends BaseMenu
{
    public function render(array $options)
    {
        $this->setDefaultOption('icon', $options, 'download-1');
        $this->setDefaultOption('label', $options, 'download.label.download');
        $this->setDefaultOption('translationDomain', $options, 'EnhavoDownloadBundle');
        $this->setDefaultOption('route', $options, 'enhavo_download_download_index');
        $this->setDefaultOption('role', $options, 'ROLE_ENHAVO_DOWNLOAD_DOWNLOAD_INDEX');

        return parent::render($options);
    }

    public function getType()
    {
        return 'download';
    }
}