<?php

namespace Enhavo\Bundle\MediaLibraryBundle\Action;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ResourceBundle\Action\AbstractActionType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\RouterInterface;

class MediaLibraryOpenSelectActionType extends AbstractActionType
{
    public function __construct(
        private readonly RouterInterface $router,
    )
    {
    }

    public function createViewData(array $options, Data $data, object $resource = null): void
    {
        $data->set('url', $this->router->generate('enhavo_media_library_admin_item_index', [
            'mode' => 'select',
        ]));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'icon' => 'cloud_upload',
            'label' => 'media_library.label.media_library',
            'translation_domain' => 'EnhavoMediaLibraryBundle',
            'model' => 'MediaLibraryOpenSelectAction'
        ]);
    }

    public static function getName(): ?string
    {
        return 'media_library_open_select';
    }
}
