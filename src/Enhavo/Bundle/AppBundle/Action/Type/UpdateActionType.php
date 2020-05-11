<?php

namespace Enhavo\Bundle\AppBundle\Action\Type;

use Enhavo\Bundle\AppBundle\Action\AbstractUrlActionType;
use Enhavo\Bundle\AppBundle\Action\ActionTypeInterface;
use Enhavo\Bundle\AppBundle\Security\Roles\RoleUtil;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UpdateActionType extends AbstractUrlActionType implements ActionTypeInterface
{
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'component' => 'create-action',
            'label' => 'label.edit',
            'translation_domain' => 'EnhavoAppBundle',
            'icon' => 'edit'
        ]);

        $resolver->setRequired(['route']);
    }

    public function getType()
    {
        return 'update';
    }
}
