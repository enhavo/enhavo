<?php
/**
 * UserMenuBuilder.php
 *
 * @since 21/09/16
 * @author gseidel
 */

namespace Enhavo\Bundle\UserBundle\Menu;

use Enhavo\Bundle\AppBundle\Menu\AbstractMenuType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserMenu extends AbstractMenuType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'icon' => 'person',
            'label' => 'user.label.user',
            'translation_domain' => 'EnhavoUserBundle',
            'route' => 'enhavo_user_user_index',
            'role' => 'ROLE_ENHAVO_USER_USER_INDEX',
        ]);
    }

    public static function getName(): ?string
    {
        return 'user_user';
    }
}
