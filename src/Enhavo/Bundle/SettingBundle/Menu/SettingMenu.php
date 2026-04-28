<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\SettingBundle\Menu;

use Enhavo\Bundle\AppBundle\Menu\AbstractMenuType;
use Enhavo\Bundle\AppBundle\Menu\Type\LinkMenuType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SettingMenu extends AbstractMenuType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'icon' => 'settings',
            'label' => 'label.setting',
            'translation_domain' => 'EnhavoSettingBundle',
            'route' => 'enhavo_setting_admin_setting_index',
            'permission' => 'ROLE_ENHAVO_SETTING_SETTING_INDEX',
            'group' => null,
            'key' => null,
        ]);

        $resolver->setNormalizer('route_parameters', function ($options, $value) {
            if ($options['group']) {
                return array_merge(['group' => $options['group']], $value);
            } elseif ($options['key']) {
                return array_merge(['key' => $options['key']], $value);
            }

            return $value;
        });

        $resolver->setNormalizer('route', function ($options, $value) {
            if ($options['group']) {
                return 'enhavo_setting_admin_setting_index';
            } elseif ($options['key']) {
                return 'enhavo_setting_admin_setting_key_update';
            }

            return $value;
        });
    }

    public static function getName(): ?string
    {
        return 'setting';
    }

    public static function getParentType(): ?string
    {
        return LinkMenuType::class;
    }
}
