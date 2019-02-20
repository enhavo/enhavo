<?php
namespace Enhavo\Bundle\AppBundle\Action\Type;

use Enhavo\Bundle\AppBundle\Action\AbstractUrlActionType;
use Enhavo\Bundle\AppBundle\Action\ActionTypeInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DuplicateActionType extends AbstractUrlActionType implements ActionTypeInterface
{
    public function createViewData(array $options, $resource = null)
    {
        $data = parent::createViewData($options, $resource);

        $data = array_merge($data, [
            'confirm' => $options['confirm'],
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
            'component' => 'duplicate-action',
            'label' => 'label.download',
            'translation_domain' => 'EnhavoAppBundle',
            'icon' => 'content_copy',
            'confirm' => true,
            'confirm_message' => 'message.duplicate.confirm',
            'confirm_label_ok' => 'label.ok',
            'confirm_label_cancel' => 'label.cancel',
        ]);
    }

    public function getType()
    {
        return 'duplicate';
    }
}