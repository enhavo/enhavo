<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\CommentBundle\Menu;

use Enhavo\Bundle\AppBundle\Menu\AbstractMenuType;
use Enhavo\Bundle\AppBundle\Menu\Type\LinkMenuType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommentMenuType extends AbstractMenuType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'icon' => 'comment',
            'label' => 'comment.label.comment',
            'translation_domain' => 'EnhavoCommentBundle',
            'route' => 'enhavo_comment_admin_comment_index',
            'permission' => 'ROLE_ENHAVO_COMMENT_COMMENT_INDEX',
        ]);
    }

    public static function getName(): ?string
    {
        return 'comment';
    }

    public static function getParentType(): ?string
    {
        return LinkMenuType::class;
    }
}
