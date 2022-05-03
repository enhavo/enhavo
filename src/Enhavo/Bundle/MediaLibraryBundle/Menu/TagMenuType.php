<?php

namespace Enhavo\Bundle\MediaLibraryBundle\Menu;

use Enhavo\Bundle\AppBundle\Menu\Menu\BaseMenu;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TagMenuType extends BaseMenu
{
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
        $resolver->setDefaults([
            'icon' => 'label_outline',
            'label' => 'media_library.label.tag',
            'translation_domain' => 'EnhavoMediaLibraryBundle',
            'route' => 'enhavo_media_library_tag_index',
            'role' => 'ROLE_ENHAVO_MEDIA_LIBRARY_TAG_INDEX'
        ]);
    }

    public function getType()
    {
        return 'media_library_tag';
    }
}
