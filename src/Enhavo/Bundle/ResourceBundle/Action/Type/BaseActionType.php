<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2019-02-19
 * Time: 02:15
 */

namespace Enhavo\Bundle\ResourceBundle\Action\Type;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ResourceBundle\Action\ActionTypeInterface;
use Enhavo\Bundle\ResourceBundle\ExpressionLanguage\ResourceExpressionLanguage;
use Enhavo\Component\Type\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class BaseActionType extends AbstractType implements ActionTypeInterface
{
    public function __construct(
        private readonly TranslatorInterface $translator,
        private readonly ResourceExpressionLanguage $expressionLanguage,
    )
    {
    }

    public function createViewData(array $options, Data $data, object $resource = null): void
    {
        $data->set('component', $options['component']);
        $data->set('model', $options['model']);
        $data->set('icon', $options['icon']);
        $data->set('label', $this->getLabel($options));

        $data->set('confirm', $options['confirm']);
        $data->set('confirmMessage', $this->translator->trans($options['confirm_message'], [], $options['translation_domain']));
        $data->set('confirmLabelOk', $this->translator->trans($options['confirm_label_ok'], [], $options['translation_domain']));
        $data->set('confirmLabelCancel', $this->translator->trans($options['confirm_label_cancel'], [], $options['translation_domain']));
    }

    public function isEnabled(array $options, object $resource = null): bool
    {
        return $this->expressionLanguage->evaluate($options['enabled'], [
            'resource' => $resource,
            'action' => $this
        ]);
    }

    public function getPermission(array $options, object $resource = null): mixed
    {
        return $this->expressionLanguage->evaluate($options['permission'], [
            'resource' => $resource,
            'action' => $this
        ]);
    }

    public function getLabel(array $options): string
    {
        return $this->translator->trans($options['label'], [], $options['translation_domain']);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'translation_domain' => null,
            'permission' => null,
            'enabled' => true,
            'confirm' => false,
            'confirm_message' => 'message.close.confirm',
            'confirm_label_ok' => 'label.ok',
            'confirm_label_cancel' => 'label.cancel',
            'component' => 'action-action'
        ]);

        $resolver->setRequired([
            'icon',
            'model',
            'label',
        ]);
    }
}
