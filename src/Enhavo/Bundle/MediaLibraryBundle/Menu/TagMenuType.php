<?php

namespace Enhavo\Bundle\MediaLibraryBundle\Menu;

use Enhavo\Bundle\AppBundle\Menu\AbstractMenuType;
use Enhavo\Bundle\AppBundle\Menu\Type\LinkMenuType;
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
            'permission' => 'ROLE_ENHAVO_MEDIA_LIBRARY_TAG_INDEX'
        ]);
    }

    public static function getName(): ?string
    {
        return 'media_library_tag';
    }

    public static function getParentType(): ?string
    {
        return LinkMenuType::class;
    }
}
