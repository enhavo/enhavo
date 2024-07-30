<?php

namespace Enhavo\Bundle\ResourceBundle\Batch\Type;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ResourceBundle\Batch\AbstractBatchType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FormBatchType extends AbstractBatchType
{
    public function createViewData(array $options, Data $data): void
    {
        $data['modal'] = [
            'component' => 'ajax-form-modal',
            'route' => $options['form_route'],
        ];
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'component' => 'batch-form',
            'model' => 'FormBatch',
        ]);

        $resolver->setRequired(['form_route']);
    }
}
