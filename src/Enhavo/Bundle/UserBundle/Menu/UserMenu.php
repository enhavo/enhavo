<?php
/**
 * UserMenuBuilder.php
 *
 * @since 21/09/16
 * @author gseidel
 */

namespace Enhavo\Bundle\UserBundle\Menu;

use Enhavo\Bundle\AppBundle\Menu\Menu\BaseMenu;

class UserMenu extends BaseMenu
{
    public function render(array $options)
    {
        $this->setDefaultOption('icon', $options, 'user');
        $this->setDefaultOption('label', $options, 'user.label.user');
        $this->setDefaultOption('translationDomain', $options, 'EnhavoUserBundle');
        $this->setDefaultOption('route', $options, 'enhavo_user_user_index');
        $this->setDefaultOption('role', $options, 'ROLE_ENHAVO_USER_USER_INDEX');

        return parent::render($options);
    }

    public function getType()
    {
        return 'user_user';
    }
}