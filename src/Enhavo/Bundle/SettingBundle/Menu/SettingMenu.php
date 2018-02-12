<?php
/**
 * SettingMenu.php
 *
 * @since 21/09/16
 * @author gseidel
 */

namespace Enhavo\Bundle\SettingBundle\Menu;

use Enhavo\Bundle\AppBundle\Menu\Menu\BaseMenu;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SettingMenu extends BaseMenu
{
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'icon' => 'cog',
            'label' => 'label.setting',
            'translationDomain' => 'EnhavoSettingBundle',
            'route' => 'enhavo_setting_setting_index',
            'role' => 'ROLE_ENHAVO_SETTING_SETTING_INDEX',
        ]);
    }

    public function getType()
    {
        return 'setting';
    }
}