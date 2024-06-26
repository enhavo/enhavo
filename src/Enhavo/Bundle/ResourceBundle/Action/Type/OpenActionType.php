<?php

namespace Enhavo\Bundle\ResourceBundle\Action\Type;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ResourceBundle\Action\AbstractActionType;
use Enhavo\Bundle\ResourceBundle\Model\ResourceInterface;
use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OpenActionType extends AbstractActionType
{
    public function createViewData(array $options, Data $data, ResourceInterface $resource = null): void
    {
        $data->set('target', $options['target']);
        $data->set('key', $options['key']);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
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

    public static function getParentType(): ?string
    {
        return UrlActionType::class;
    }

    public static function getName(): ?string
    {
        return 'open';
    }
}
