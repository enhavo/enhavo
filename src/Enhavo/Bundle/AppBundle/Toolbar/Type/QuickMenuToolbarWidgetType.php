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

class QuickMenuToolbarWidgetType extends AbstractToolbarWidgetType
{
    public function __construct(
        private readonly TranslatorInterface $translator,
        private readonly RouterInterface $router,
    ) {
    }

    public function createViewData(array $options, Data $data): void
    {
        $data['menu'] = $this->createMenu($options['menu']);
        $data['icon'] = $options['icon'];
    }

    private function createMenu(array $menu): array
    {
        $data = [];
        foreach ($menu as $config) {
            $data[] = [
                'target' => $this->getMenuTarget($config),
                'url' => $this->getMenuUrl($config),
                'label' => $this->getMenuLabel($config),
            ];
        }

        return $data;
    }

    private function getMenuUrl($config)
    {
        if (isset($config['url'])) {
            return $config['url'];
        } elseif (isset($config['route'])) {
            return $this->router->generate($config['route'], $config['route_parameters'] ?? []);
        }

        throw new \InvalidArgumentException('A menu of quick menu need either url or route option');
    }

    private function getMenuTarget($config)
    {
        if (!isset($config['target'])) {
            return '_frame';
        }

        if (!in_array($config['target'], ['_frame', '_blank', '_self'])) {
            throw new \InvalidArgumentException(sprintf('Target of menu from quick menu must be _frame, _blank or _self, but "%s" given', $config['target']));
        }

        return $config['target'];
    }

    private function getMenuLabel($config): string
    {
        if (!isset($config['label'])) {
            throw new \InvalidArgumentException(sprintf('A menu of quick need a label'));
        }

        return $this->translator->trans($config['label'], [], $config['translation_domain'] ?? null);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'icon' => null,
            'component' => 'toolbar-widget-quick-menu',
            'model' => 'QuickMenuToolbarWidget',
        ]);

        $resolver->setRequired([
            'menu',
        ]);
    }

    public static function getName(): ?string
    {
        return 'quick_menu';
    }
}
