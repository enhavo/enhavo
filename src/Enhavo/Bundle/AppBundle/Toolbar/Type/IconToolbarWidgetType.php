<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\AppBundle\Toolbar\Type;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\AppBundle\Toolbar\AbstractToolbarWidgetType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class IconToolbarWidgetType extends AbstractToolbarWidgetType
{
    public function __construct(
        private readonly TranslatorInterface $translator,
        private readonly RouterInterface $router,
    ) {
    }

    public function createViewData(array $options, Data $data): void
    {
        $data['label'] = $this->getLabel($options);
        $data['target'] = $this->getTarget($options);
        $data['icon'] = $options['icon'];
        $data['url'] = $this->getUrl($options);
    }

    private function getUrl($config)
    {
        if (null !== $config['url']) {
            return $config['url'];
        } elseif (null !== $config['route']) {
            return $this->router->generate($config['route'], $config['route_parameters'] ?? []);
        }

        throw new \InvalidArgumentException(sprintf('Either url or route option must be defined'));
    }

    private function getTarget($config)
    {
        if (!in_array($config['target'], ['_frame', '_blank', '_self'])) {
            throw new \InvalidArgumentException(sprintf('Target must be _frame, _blank or _self, but "%s" given', $config['target']));
        }

        return $config['target'];
    }

    private function getLabel($config): string
    {
        return $this->translator->trans($config['label'], [], $config['translation_domain'] ?: null);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'icon' => null,
            'component' => 'toolbar-widget-icon',
            'route' => null,
            'url' => null,
            'route_parameters' => [],
            'translation_domain' => null,
            'target' => '_frame',
            'label' => null,
            'model' => 'IconToolbarWidget',
        ]);

        $resolver->setRequired([
            'icon',
        ]);
    }

    public static function getName(): ?string
    {
        return 'icon';
    }
}
