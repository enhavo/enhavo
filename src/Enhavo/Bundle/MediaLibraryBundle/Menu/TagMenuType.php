<?php

namespace Enhavo\Bundle\MediaLibraryBundle\Menu;

use Enhavo\Bundle\AppBundle\Menu\AbstractMenuType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TagMenuType extends AbstractMenuType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'icon' => 'label_outline',
            'label' => 'media_library.label.tag',
            'translation_domain' => 'EnhavoMediaLibraryBundle',
            'route' => 'enhavo_media_library_admin_tag_index',
            'role' => 'ROLE_ENHAVO_MEDIA_LIBRARY_TAG_INDEX'
        ]);
    }

    public static function getName(): ?string
    {
        return 'media_library_tag';
    }
}
