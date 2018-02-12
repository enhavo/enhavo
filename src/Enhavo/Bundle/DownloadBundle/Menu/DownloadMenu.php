<?php
/**
 * DownloadMenu.php
 *
 * @since 21/09/16
 * @author gseidel
 */

namespace Enhavo\Bundle\DownloadBundle\Menu;

use Enhavo\Bundle\AppBundle\Menu\Menu\BaseMenu;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DownloadMenu extends BaseMenu
{
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'icon' => 'download-1',
            'label' => 'download.label.download',
            'translationDomain' => 'EnhavoDownloadBundle',
            'route' => 'enhavo_download_download_index',
            'role' => 'ROLE_ENHAVO_DOWNLOAD_DOWNLOAD_INDEX',
        ]);
    }

    public function getType()
    {
        return 'download';
    }
}