<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\NavigationBundle\Twig;

use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class NavigationExtension extends AbstractExtension
{
    private ?Environment $twig = null;

    public function __construct(
        private readonly array $renderSets,
    ) {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('navigation_node_render', [$this, 'render'], ['is_safe' => ['html']]),
        ];
    }

    public function setTwig(Environment $twig): void
    {
        $this->twig = $twig;
    }

    public function render(?array $node, string|array|null $renderSet = null): string
    {
        if (null === $node) {
            return '';
        }

        $template = null;
        if (is_array($renderSet) && isset($renderSet[$node['name']])) {
            $template = $renderSet[$node['name']];
        } elseif (is_string($renderSet) && isset($this->renderSets[$renderSet][$node['name']])) {
            $template = $this->renderSets[$renderSet][$node['name']];
        } elseif (isset($node['template'])) {
            $template = $node['template'];
        }

        if (null === $template) {
            throw new \Exception('Unable to determine navigation template');
        }

        return $this->twig->render($template, [
            'node' => $node,
        ]);
    }
}
