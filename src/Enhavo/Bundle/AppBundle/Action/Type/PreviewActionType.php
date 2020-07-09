<?php

/**
 * PreviewButton.php
 *
 * @since 29/05/16
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\Action\Type;

use Enhavo\Bundle\AppBundle\Action\AbstractUrlActionType;
use Enhavo\Bundle\AppBundle\Action\ActionTypeInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PreviewActionType extends AbstractUrlActionType implements ActionTypeInterface
{
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'component' => 'preview-action',
            'label' => 'label.preview',
            'translation_domain' => 'EnhavoAppBundle',
            'icon' => 'remove_red_eye',
            'append_id' => true
        ]);

        $resolver->setRequired(['route']);
    }

    public function getType()
    {
        return 'preview';
    }
}
