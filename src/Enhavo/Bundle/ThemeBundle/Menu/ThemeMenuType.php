<?php

namespace Enhavo\Bundle\ThemeBundle\Menu;

use Enhavo\Bundle\AppBundle\Menu\Menu\BaseMenu;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ThemeMenuType extends BaseMenu
{
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'icon' => 'web',
            'label' => 'theme.label.theme',
            'translation_domain' => 'EnhavoThemeBundle',
            'route' => 'enhavo_theme_theme_index',
            'role' => 'ROLE_ENHAVO_THEME_THEME_INDEX'
        ]);
    }

    public function getType()
    {
        return 'theme';
    }
}
