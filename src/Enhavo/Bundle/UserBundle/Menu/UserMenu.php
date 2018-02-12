<?php
/**
 * UserMenuBuilder.php
 *
 * @since 21/09/16
 * @author gseidel
 */

namespace Enhavo\Bundle\UserBundle\Menu;

use Enhavo\Bundle\AppBundle\Menu\Menu\BaseMenu;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserMenu extends BaseMenu
{
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'icon' => 'user',
            'label' => 'user.label.user',
            'translationDomain' => 'EnhavoUserBundle',
            'route' => 'enhavo_user_user_index',
            'role' => 'ROLE_ENHAVO_USER_USER_INDEX',
        ]);
    }

    public function getType()
    {
        return 'user_user';
    }
}