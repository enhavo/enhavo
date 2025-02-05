<?php

namespace Enhavo\Bundle\MediaLibraryBundle\Action;

use Enhavo\Bundle\ResourceBundle\Action\AbstractActionType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MediaLibrarySelectActionType extends AbstractActionType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'icon' => 'check',
            'label' => 'media_library.label.select',
            'translation_domain' => 'EnhavoMediaLibraryBundle',
            'model' => 'MediaLibrarySelectAction'
        ]);
    }

    public static function getName(): ?string
    {
        return 'media_library_select';
    }
}
