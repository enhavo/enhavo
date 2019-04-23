<?php

/**
 * EventAction.php
 *
 * @since 29/05/16
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\Action\Type;

use Enhavo\Bundle\AppBundle\Action\AbstractActionType;
use Enhavo\Bundle\AppBundle\Action\ActionTypeInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EventActionType extends AbstractActionType implements ActionTypeInterface
{
    public function createViewData(array $options, $resource = null)
    {
        $data = parent::createViewData($options, $resource);

        $data = array_merge($data, [
            'confirm' => $options['confirm'],
            'confirm_on_change' => $options['confirm_changes'],
            'event' => $options['event'],
            'confirm_message' => $this->translator->trans($options['confirm_message'], [], $options['translation_domain']),
            'confirm_label_ok' => $this->translator->trans($options['confirm_label_ok'], [], $options['translation_domain']),
            'confirm_label_cancel' => $this->translator->trans($options['confirm_label_cancel'], [], $options['translation_domain']),
        ]);

        return $data;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

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

    public function getType()
    {
        return 'event';
    }
}
