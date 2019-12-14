<?php

namespace Enhavo\Bundle\AppBundle\Batch;

use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class AbstractFormBatchType extends AbstractBatchType
{
    public function createViewData(array $options, $resource = null)
    {
        $data = parent::createViewData($options, $resource);
        $data['modal'] = [
            'component' => 'ajax-form-modal',
            'route' => $options['form_route'],
        ];
        return $data;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'component' => 'batch-form'
        ]);

        $resolver->setRequired(['form_route']);
    }
}
