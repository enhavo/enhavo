<?php
/**
 * Created by PhpStorm.
 * User: philippsester
 * Date: 25.05.19
 * Time: 14:02
 */

namespace Enhavo\Bundle\SidebarBundle\Menu;

use Enhavo\Bundle\AppBundle\Menu\AbstractMenuType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SidebarMenu extends AbstractMenuType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'icon' => 'view_column',
            'label' => 'sidebar.label.sidebar',
            'translation_domain' => 'EnhavoSidebarBundle',
            'route' => 'enhavo_sidebar_sidebar_index',
            'role' => 'ROLE_ENHAVO_SIDEBAR_SIDEBAR_INDEX',
        ]);
    }

    public static function getName(): ?string
    {
        return 'sidebar';
    }
}
