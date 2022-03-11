<?php

namespace Enhavo\Bundle\MediaLibraryBundle\Menu;

use Enhavo\Bundle\AppBundle\Menu\Menu\ListMenu;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MediaLibraryRootMenuType extends ListMenu
{
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'icon' => 'image',
            'label' => 'media_library.label.media_library',
            'translation_domain' => 'EnhavoMediaLibraryBundle',
            'children' => [
                'library' => [
                    'type' => 'media_library_library'
                ],
                'tag' => [
                    'type' => 'media_library_tag'
                ],
            ]
        ]);
    }

    public function getType()
    {
        return 'media_library';
    }
}
