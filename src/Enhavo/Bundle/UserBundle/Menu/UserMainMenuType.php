<?php
/**
 * UserMenuBuilder.php
 *
 * @since 21/09/16
 * @author gseidel
 */

namespace Enhavo\Bundle\UserBundle\Menu;

use Enhavo\Bundle\AppBundle\Menu\AbstractMenuType;
use Enhavo\Bundle\AppBundle\Menu\Type\LinkMenuType;
use Enhavo\Bundle\AppBundle\Menu\Type\ListMenuType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserMainMenuType extends AbstractMenuType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'icon' => 'people',
            'label' => 'user.label.user',
            'translation_domain' => 'EnhavoUserBundle',
            'items' => [
                'user_user' => [
                    'type' => 'user_user'
                ],
                'user_group' => [
                    'type' => 'user_group'
                ]
            ]
        ]);
    }

    public static function getName(): ?string
    {
        return 'user';
    }

    public static function getParentType(): ?string
    {
        return ListMenuType::class;
    }
}
