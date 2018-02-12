<?php
/**
 * SettingMenu.php
 *
 * @since 21/09/16
 * @author gseidel
 */

namespace Enhavo\Bundle\SettingBundle\Menu;

use Enhavo\Bundle\AppBundle\Menu\Menu\BaseMenu;

class SettingMenu extends BaseMenu
{
    public function render(array $options)
    {
        $this->setOption('icon', $options, 'cog');
        $this->setOption('label', $options, 'label.setting');
        $this->setOption('translationDomain', $options, 'EnhavoSettingBundle');
        $this->setOption('route', $options, 'enhavo_setting_setting_index');
        $this->setOption('role', $options, 'ROLE_ENHAVO_SETTING_SETTING_INDEX');

        return parent::render($options);
    }

    public function getType()
    {
        return 'setting';
    }
}