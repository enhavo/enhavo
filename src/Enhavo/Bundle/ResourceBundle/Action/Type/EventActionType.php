<?php

/**
 * EventAction.php
 *
 * @since 29/05/16
 * @author gseidel
 */

namespace Enhavo\Bundle\ResourceBundle\Action\Type;

use Enhavo\Bundle\ResourceBundle\Action\ActionTypeInterface;
use Enhavo\Bundle\ResourceBundle\Action\AbstractActionType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EventActionType extends AbstractActionType implements ActionTypeInterface
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'component' => 'event-action',
            'translation_domain' => 'EnhavoAppBundle',
            'confirm' => false,
            'confirm_changes' => true,
            'confirm_message' => 'message.close.confirm',
            'confirm_label_ok' => 'label.ok',
            'confirm_label_cancel' => 'label.cancel',
        ]);

        $resolver->setRequired(['icon', 'label', 'event']);
    }

    public static function getName(): ?string
    {
        return 'event';
    }
}
