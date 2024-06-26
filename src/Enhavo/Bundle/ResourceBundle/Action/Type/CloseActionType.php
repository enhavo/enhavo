<?php

/**
 * CancelButton.php
 *
 * @since 29/05/16
 * @author gseidel
 */

namespace Enhavo\Bundle\ResourceBundle\Action\Type;

use Enhavo\Bundle\ResourceBundle\Action\AbstractActionType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CloseActionType extends AbstractActionType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'component' => 'close-action',
            'label' => 'label.close',
            'translation_domain' => 'EnhavoAppBundle',
            'icon' => 'close',
        ]);
    }

    public static function getName(): ?string
    {
        return 'close';
    }
}
