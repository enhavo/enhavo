<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2019-02-19
 * Time: 02:15
 */

namespace Enhavo\Bundle\ResourceBundle\Action\Type;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ResourceBundle\Action\ActionLanguageExpression;
use Enhavo\Bundle\ResourceBundle\Action\ActionTypeInterface;
use Enhavo\Bundle\ResourceBundle\Model\ResourceInterface;
use Enhavo\Component\Type\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class BaseActionType extends AbstractType implements ActionTypeInterface
{
    public function __construct(
        private readonly TranslatorInterface $translator,
        private readonly ActionLanguageExpression $actionLanguageExpression,
    )
    {
    }

    public function createViewData(array $options, Data $data, ResourceInterface $resource = null): void
    {
        $data->set('component', $options['component']);
        $data->set('icon', $options['icon']);
        $data->set('label', $this->getLabel($options));

        $data->set('confirm', $options['confirm']);
        $data->set('confirm_on_change', $options['confirm_changes']);
        $data->set('confirm_message', $this->translator->trans($options['confirm_message'], [], $options['translation_domain']));
        $data->set('confirm_label_ok', $this->translator->trans($options['confirm_label_ok'], [], $options['translation_domain']));
        $data->set('confirm_label_cancel', $this->translator->trans($options['confirm_label_cancel'], [], $options['translation_domain']));
    }

    public function isEnabled(array $options, ResourceInterface $resource = null): bool
    {
        if (!$options['enabled'] === false && $options['condition']) {
            $enabled = !$this->actionLanguageExpression->evaluate($options['condition'], [
                'resource' => $resource,
                'action' => $this
            ]);
        } else if (preg_match('/^exp:/', $options['enabled'])) {
            $enabled = $this->actionLanguageExpression->evaluate(substr($options['enabled'], 4), [
                'resource' => $resource,
                'action' => $this
            ]);
        } else {
            $enabled = $options['enabled'];
        }

        return $enabled;
    }

    public function getPermission(array $options, ResourceInterface $resource = null): mixed
    {
        return $options['permission'];
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
            'condition' => null,
            'confirm' => false,
            'confirm_changes' => true,
            'confirm_message' => 'message.close.confirm',
            'confirm_label_ok' => 'label.ok',
            'confirm_label_cancel' => 'label.cancel',
        ]);

        $resolver->setRequired([
            'icon',
            'component',
            'label'
        ]);
    }
}
