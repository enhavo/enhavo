<?php

/**
 * CancelButton.php
 *
 * @since 29/05/16
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\Action\Type;

use Enhavo\Bundle\AppBundle\Action\AbstractActionType;
use Enhavo\Bundle\AppBundle\Action\ActionTypeInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SaveActionType extends AbstractActionType implements ActionTypeInterface
{
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'component' => 'save-action',
            'label' => 'label.save',
            'translation_domain' => 'EnhavoAppBundle',
            'icon' => 'save',
            'route' => null
        ]);
    }

    public function getType()
    {
        return 'save';
    }
}
