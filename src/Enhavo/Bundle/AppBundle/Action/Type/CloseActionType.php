<?php

namespace Enhavo\Bundle\AppBundle\Action\Type;

use Enhavo\Bundle\ResourceBundle\Action\AbstractActionType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author gseidel
 */
class CloseActionType extends AbstractActionType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'label' => 'label.close',
            'translation_domain' => 'EnhavoAppBundle',
            'icon' => 'close',
            'model' => 'CloseAction',
        ]);
    }

    public static function getName(): ?string
    {
        return 'close';
    }
}
