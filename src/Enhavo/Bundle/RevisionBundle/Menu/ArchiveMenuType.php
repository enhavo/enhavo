<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\RevisionBundle\Menu;

use Enhavo\Bundle\AppBundle\Menu\AbstractMenuType;
use Enhavo\Bundle\AppBundle\Menu\Type\LinkMenuType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArchiveMenuType extends AbstractMenuType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'icon' => 'archive',
            'label' => 'archive.label.archive',
            'translation_domain' => 'EnhavoRevisionBundle',
            'route' => 'enhavo_revision_admin_archive_index',
            'permission' => 'ROLE_ENHAVO_REVISION_ARCHIVE_INDEX',
        ]);
    }

    public static function getName(): ?string
    {
        return 'revision_archive';
    }

    public static function getParentType(): ?string
    {
        return LinkMenuType::class;
    }
}
