<?php

namespace Enhavo\Bundle\AppBundle\Action\Type;

use Enhavo\Bundle\AppBundle\Action\AbstractActionType;
use Enhavo\Bundle\AppBundle\Action\ActionTypeInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FilterActionType extends AbstractActionType implements ActionTypeInterface
{
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'component' => 'filter-action',
            'label' => 'label.filter',
            'translation_domain' => 'EnhavoAppBundle',
            'icon' => 'filter_list'
        ]);
    }

    public function getType()
    {
        return 'filter';
    }
}
