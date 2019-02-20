<?php

/**
 * PreviewButton.php
 *
 * @since 29/05/16
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\Action\Type;

use Enhavo\Bundle\AppBundle\Action\AbstractActionType;
use Enhavo\Bundle\AppBundle\Action\ActionTypeInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PreviewActionType extends AbstractActionType implements ActionTypeInterface
{
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'component' => 'preview-action',
            'label' => 'label.preview',
            'translation_domain' => 'EnhavoAppBundle',
            'icon' => 'remove_red_eye'
        ]);
    }

    public function getType()
    {
        return 'preview';
    }
}