<?php

namespace Enhavo\Bundle\AppBundle\Action\Type;

use Enhavo\Bundle\ResourceBundle\Action\AbstractActionType;
use Enhavo\Bundle\ResourceBundle\Action\ActionTypeInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FilterActionType extends AbstractActionType implements ActionTypeInterface
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'label' => 'label.filter',
            'translation_domain' => 'EnhavoAppBundle',
            'icon' => 'filter_list',
            'model' => 'FilterAction',
            'component' => 'action-filter',
        ]);
    }

    public static function getName(): ?string
    {
        return 'filter';
    }
}
