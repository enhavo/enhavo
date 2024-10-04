<?php

namespace Enhavo\Bundle\MediaLibraryBundle\Filter;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ResourceBundle\Filter\AbstractFilterType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MediaLibraryUnusedFileFilterType extends AbstractFilterType
{
    public function __construct()
    {
    }

    public function createViewData($options, Data $data): void
    {

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'component' => 'filter-media-library-choice',
            'model' => 'BooleanFilter',
            'initial_value' => null,
        ]);
    }

    public static function getName(): ?string
    {
        return 'media_library_unused_file';
    }
}
