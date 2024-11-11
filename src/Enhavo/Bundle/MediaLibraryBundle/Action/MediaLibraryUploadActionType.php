<?php

namespace Enhavo\Bundle\MediaLibraryBundle\Action;

use Enhavo\Bundle\ResourceBundle\Action\AbstractActionType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MediaLibraryUploadActionType extends AbstractActionType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'icon' => 'cloud_upload',
            'label' => 'media_library.label.upload',
            'translation_domain' => 'EnhavoMediaLibraryBundle',
            'model' => 'MediaLibraryUploadAction'
        ]);
    }

    public static function getName(): ?string
    {
        return 'media_library_upload';
    }
}
