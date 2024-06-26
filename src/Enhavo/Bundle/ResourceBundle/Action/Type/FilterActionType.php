<?php

namespace Enhavo\Bundle\ResourceBundle\Action\Type;

use Enhavo\Bundle\ResourceBundle\Action\ActionTypeInterface;
use Enhavo\Bundle\ResourceBundle\Action\AbstractActionType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FilterActionType extends AbstractActionType implements ActionTypeInterface
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'component' => 'filter-action',
            'label' => 'label.filter',
            'translation_domain' => 'EnhavoAppBundle',
            'icon' => 'filter_list'
        ]);
    }

    public static function getName(): ?string
    {
        return 'filter';
    }
}
