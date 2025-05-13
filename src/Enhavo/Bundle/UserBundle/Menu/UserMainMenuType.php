<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\UserBundle\Menu;

use Enhavo\Bundle\AppBundle\Menu\AbstractMenuType;
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
                    'type' => 'user_user',
                ],
                'user_group' => [
                    'type' => 'user_group',
                ],
            ],
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
