<?php

/**
 * LibraryMenu.php
 *
 * @since 02/10/18
 * @author gseidel
 */

namespace Enhavo\Bundle\MediaLibraryBundle\Menu;

use Enhavo\Bundle\AppBundle\Menu\AbstractMenuType;
use Enhavo\Bundle\AppBundle\Menu\Type\LinkMenuType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MediaLibraryMenu extends AbstractMenuType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'icon' => 'image',
            'label' => 'media_library.label.library',
            'translation_domain' => 'EnhavoMediaLibraryBundle',
            'route' => 'enhavo_media_library_admin_item_index',
            'permission' => 'ROLE_ENHAVO_MEDIA_LIBRARY_FILE_INDEX',
        ]);
    }

    public static function getName(): ?string
    {
        return 'media_library_library';
    }

    public static function getParentType(): ?string
    {
        return LinkMenuType::class;
    }
}
