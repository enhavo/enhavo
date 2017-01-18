<?php
/**
 * UserMenuBuilder.php
 *
 * @since 21/09/16
 * @author gseidel
 */

namespace Enhavo\Bundle\UserBundle\Menu;

use Enhavo\Bundle\AppBundle\Menu\Builder\BaseMenuBuilder;

class UserMenuBuilder extends BaseMenuBuilder
{
    public function createMenu(array $options)
    {
        $this->setOption('icon', $options, 'user');
        $this->setOption('label', $options, 'user.label.user');
        $this->setOption('translationDomain', $options, 'EnhavoUserBundle');
        $this->setOption('route', $options, 'enhavo_user_user_index');
        $this->setOption('role', $options, 'ROLE_ENHAVO_USER_USER_INDEX');
        return parent::createMenu($options);
    }

    public function getType()
    {
        return 'user_user';
    }
}