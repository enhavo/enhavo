<?php

namespace Enhavo\Bundle\UserBundle\Menu;

use Enhavo\Bundle\AppBundle\Menu\AbstractMenuType;
use Enhavo\Bundle\AppBundle\Menu\Type\LinkMenuType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author gseidel
 */
class UserMenu extends AbstractMenuType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'icon' => 'person',
            'label' => 'user.label.user',
            'translation_domain' => 'EnhavoUserBundle',
            'route' => 'enhavo_user_admin_user_index',
            'permission' => 'ROLE_ENHAVO_USER_USER_INDEX',
        ]);
    }

    public static function getName(): ?string
    {
        return 'user_user';
    }

    public static function getParentType(): ?string
    {
        return LinkMenuType::class;
    }
}
