<?php

namespace Enhavo\Bundle\AppBundle\Action\Type;

use Enhavo\Bundle\AppBundle\Action\AbstractUrlActionType;
use Enhavo\Bundle\AppBundle\Action\ActionTypeInterface;
use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class ExportModalActionType
 * @package SummitDatabaseBundle\Action
 */
class OpenActionType extends AbstractUrlActionType implements ActionTypeInterface
{
    /**
     * @param array $options
     * @param null $resource
     * @return array|string
     */
    public function createViewData(array $options, $resource = null)
    {
        $data = parent::createViewData($options, $resource);
        $data = array_merge($data, [
            'target' => $options['target'],
            'key' => $options['view_key'],
            'confirm' => $options['confirm'],
            'confirmMessage' => $this->translator->trans($options['confirm_message'], [], $options['translation_domain']),
            'confirmLabelOk' => $this->translator->trans($options['confirm_label_ok'], [], $options['translation_domain']),
            'confirmLabelCancel' => $this->translator->trans($options['confirm_label_cancel'], [], $options['translation_domain']),
        ]);
        return $data;
    }

    protected function getUrl(array $options, $resource = null)
    {
        if ($options['url']) {
            return $options['url'];
        }

        return parent::getUrl($options, $resource);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'component' => 'open-action',
            'label' => 'label.open',
            'translation_domain' => 'EnhavoAppBundle',
            'icon' => 'arrow_forward',
            'target' => '_self',
            'view_key' => null,
            'url' => null,
            'route' => null,
            'confirm' => false,
            'confirm_message' => 'message.open.confirm',
            'confirm_label_ok' => 'label.ok',
            'confirm_label_cancel' => 'label.cancel',
        ]);

        $resolver->setNormalizer('route', function($options, $value) {
            if ($options['url'] === null && $value === null) {
                throw new InvalidOptionsException('Need to configure "route" or "url" option');
            }
            return $value;
        });
    }

    /**
     * @return string
     */
    public function getType()
    {
        return 'open';
    }
}
