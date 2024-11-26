<?php
/**
 * GroupMenuBuilder.php
 *
 * @since 21/09/16
 * @author gseidel
 */

namespace Enhavo\Bundle\UserBundle\Menu;

use Enhavo\Bundle\AppBundle\Menu\AbstractMenuType;
use Enhavo\Bundle\AppBundle\Menu\Type\LinkMenuType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GroupMenu extends AbstractMenuType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'icon' => 'people_outline',
            'label' => 'group.label.group',
            'translation_domain' => 'EnhavoUserBundle',
            'route' => 'enhavo_user_admin_group_index',
            'permission' => 'ROLE_ENHAVO_USER_GROUP_INDEX',
        ]);
    }

    public static function getName(): ?string
    {
        return 'user_group';
    }

    public static function getParentType(): ?string
    {
        return LinkMenuType::class;
    }
}
