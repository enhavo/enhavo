<?php

/**
 * LibraryMenu.php
 *
 * @since 02/10/18
 * @author gseidel
 */

namespace Enhavo\Bundle\MediaLibraryBundle\Menu;

use Enhavo\Bundle\AppBundle\Menu\AbstractMenuType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MediaLibraryMenu extends AbstractMenuType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'icon' => 'image',
            'label' => 'media_library.label.library',
            'translation_domain' => 'EnhavoMediaLibraryBundle',
            'route' => 'enhavo_media_library_admin_file_index',
            'role' => 'ROLE_ENHAVO_MEDIA_LIBRARY_FILE_INDEX',
        ]);
    }

    public static function getName(): ?string
    {
        return 'media_library_library';
    }
}
