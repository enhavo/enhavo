<?php

namespace Enhavo\Bundle\AppBundle\Action\Type;

use Enhavo\Bundle\AppBundle\Action\AbstractUrlActionType;
use Enhavo\Bundle\AppBundle\Action\ActionTypeInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UpdateActionType extends AbstractUrlActionType implements ActionTypeInterface
{
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'component' => 'open-action',
            'label' => 'label.edit',
            'translation_domain' => 'EnhavoAppBundle',
            'icon' => 'edit',
        ]);

        $resolver->setRequired(['route']);
    }

    public function getType()
    {
        return 'update';
    }
}
