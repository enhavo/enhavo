<?php
/**
 * GroupMenuBuilder.php
 *
 * @since 21/09/16
 * @author gseidel
 */

namespace Enhavo\Bundle\UserBundle\Menu;

use Enhavo\Bundle\AppBundle\Menu\Menu\BaseMenu;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GroupMenu extends BaseMenu
{
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'icon' => 'people_outline',
            'label' => 'group.label.group',
            'translation_domain' => 'EnhavoUserBundle',
            'route' => 'enhavo_user_group_index',
            'role' => 'ROLE_ENHAVO_USER_GROUP_INDEX',
        ]);
    }

    public function getType()
    {
        return 'user_group';
    }
}