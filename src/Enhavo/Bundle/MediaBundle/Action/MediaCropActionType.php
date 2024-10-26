<?php

namespace Enhavo\Bundle\MediaBundle\Action;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ResourceBundle\Action\AbstractActionType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MediaCropActionType extends AbstractActionType
{
    public function createViewData(array $options, Data $data, object $resource = null): void
    {
        $data->set('method', $options['method']);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'model' => 'MediaCropAction',
        ]);

        $resolver->setRequired([
            'method'
        ]);
    }
}
