<?php

namespace Enhavo\Bundle\MediaBundle\Action;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ResourceBundle\Action\AbstractActionType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MediaDownloadActionType extends AbstractActionType
{
    public function createViewData(array $options, Data $data, object $resource = null): void
    {

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'label' => 'media.form.action.download',
            'translation_domain' => 'EnhavoMediaBundle',
            'icon' => 'cloud_download',
            'component' => 'action-media-form',
            'model' => 'MediaDownloadAction',
        ]);
    }

    public static function getName(): ?string
    {
        return 'media_download';
    }
}
