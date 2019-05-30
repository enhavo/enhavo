<?php
/**
 * Created by PhpStorm.
 * User: philippsester
 * Date: 25.05.19
 * Time: 14:02
 */

namespace Enhavo\Bundle\SidebarBundle\Menu;

use Enhavo\Bundle\AppBundle\Menu\Menu\BaseMenu;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SidebarMenu extends BaseMenu
{
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'icon' => 'view_column',
            'label' => 'sidebar.label.sidebar',
            'translation_domain' => 'EnhavoSidebarBundle',
            'route' => 'enhavo_sidebar_sidebar_index',
            'role' => 'ROLE_ENHAVO_SIDEBAR_SIDEBAR_INDEX',
        ]);
    }

    public function getType()
    {
        return 'sidebar';
    }
}