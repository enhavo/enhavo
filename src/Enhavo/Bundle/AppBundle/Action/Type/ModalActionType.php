<?php

namespace Enhavo\Bundle\AppBundle\Action\Type;

use Enhavo\Bundle\AppBundle\Action\AbstractActionType;
use Enhavo\Bundle\AppBundle\Action\ActionTypeInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ModalActionType extends AbstractActionType implements ActionTypeInterface
{
    public function createViewData(array $options, $resource = null)
    {
        $data = parent::createViewData($options, $resource);

        if(!isset($options['modal']['type'])) {
            throw new \InvalidArgumentException("Modal action needs a configuration with modal type");
        }
        $data['modal'] = $options['modal']['type'];
        $data['options'] = $options['modal'];

        return $data;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'component' => 'modal-action',
        ]);

        $resolver->setRequired(['modal']);
    }

    public function getType()
    {
        return 'modal';
    }
}
