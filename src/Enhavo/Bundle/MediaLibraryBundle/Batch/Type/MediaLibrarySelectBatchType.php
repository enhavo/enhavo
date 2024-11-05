<?php

namespace Enhavo\Bundle\MediaLibraryBundle\Batch\Type;

use Enhavo\Bundle\ResourceBundle\Batch\AbstractBatchType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MediaLibrarySelectBatchType extends AbstractBatchType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'model' => 'MediaLibrarySelectBatch',
            'label' => 'media_library.label.select',
            'translation_domain' => 'EnhavoMediaLibraryBundle',
        ]);
    }
}
