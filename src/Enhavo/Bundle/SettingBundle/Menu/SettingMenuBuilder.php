<?php
/**
 * SettingMenuBuilder.php
 *
 * @since 21/09/16
 * @author gseidel
 */

namespace Enhavo\Bundle\SettingBundle\Menu;


use Enhavo\Bundle\AppBundle\Menu\Builder\BaseMenuBuilder;

class SettingMenuBuilder extends BaseMenuBuilder
{
    public function createMenu(array $options)
    {
        $this->setOption('icon', $options, 'cog');
        $this->setOption('label', $options, 'label.setting');
        $this->setOption('translationDomain', $options, 'EnhavoSettingBundle');
        $this->setOption('route', $options, 'enhavo_setting_setting_index');
        $this->setOption('role', $options, 'ROLE_ENHAVO_SETTING_SETTING_INDEX');
        return parent::createMenu($options);
    }

    public function getType()
    {
        return 'setting';
    }
}