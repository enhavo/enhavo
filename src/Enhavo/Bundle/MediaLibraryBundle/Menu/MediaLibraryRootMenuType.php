<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\MediaLibraryBundle\Menu;

use Enhavo\Bundle\AppBundle\Menu\AbstractMenuType;
use Enhavo\Bundle\AppBundle\Menu\Type\ListMenuType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MediaLibraryRootMenuType extends AbstractMenuType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'icon' => 'image',
            'label' => 'media_library.label.media_library',
            'translation_domain' => 'EnhavoMediaLibraryBundle',
            'items' => [
                'library' => [
                    'type' => 'media_library_library',
                ],
                'tag' => [
                    'type' => 'media_library_tag',
                ],
            ],
        ]);
    }

    public static function getName(): ?string
    {
        return 'media_library';
    }

    public static function getParentType(): ?string
    {
        return ListMenuType::class;
    }
}
