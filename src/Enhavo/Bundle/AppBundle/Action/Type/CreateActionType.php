<?php

/**
 * CreateAction.php
 *
 * @since 30/05/16
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\Action\Type;

use Enhavo\Bundle\AppBundle\Action\AbstractUrlActionType;
use Enhavo\Bundle\AppBundle\Action\ActionTypeInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CreateActionType extends AbstractUrlActionType implements ActionTypeInterface
{
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'component' => 'create-action',
            'icon' => 'add_circle_outline',
            'label' => 'label.create',
            'translation_domain' => 'EnhavoAppBundle',
        ]);
    }

    public function getType()
    {
        return 'create';
    }
}