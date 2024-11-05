<?php

namespace Enhavo\Bundle\MediaLibraryBundle\Action;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ResourceBundle\Action\AbstractActionType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\RouterInterface;

class MediaLibrarySelectActionType extends AbstractActionType
{
    public function __construct(
        private readonly RouterInterface $router,
    )
    {
    }

    public function createViewData(array $options, Data $data, object $resource = null): void
    {

    }

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
