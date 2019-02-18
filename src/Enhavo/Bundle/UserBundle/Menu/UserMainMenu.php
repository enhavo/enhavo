<?php
/**
 * UserMenuBuilder.php
 *
 * @since 21/09/16
 * @author gseidel
 */

namespace Enhavo\Bundle\UserBundle\Menu;

use Enhavo\Bundle\AppBundle\Menu\Menu\ListMenu;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserMainMenu extends ListMenu
{
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'icon' => 'people',
            'label' => 'user.label.user',
            'translation_domain' => 'EnhavoUserBundle',
            'children' => [
                'user' => [
                    'type' => 'user_user'
                ],
                'group' => [
                    'type' => 'user_group'
                ]
            ]
        ]);
    }

    public function getType()
    {
        return 'user';
    }
}