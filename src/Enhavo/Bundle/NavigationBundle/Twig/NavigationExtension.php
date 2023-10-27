<?php

namespace Enhavo\Bundle\NavigationBundle\Twig;

use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class NavigationExtension extends AbstractExtension
{
    private ?Environment $twig = null;

    public function __construct(
        private readonly array $renderSets
    ) {}

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
        if ($node === null) {
            return '';
        }

        $template = null;
        if (is_array($renderSet) && isset($renderSet[$node['name']])) {
            $template = $renderSet[$node['name']];
        } else if (is_string($renderSet) && isset($this->renderSets[$renderSet][$node['name']])) {
            $template = $this->renderSets[$renderSet][$node['name']];
        } else if (isset($node['template'])) {
            $template = $node['template'];
        }

        if ($template === null) {
            throw new \Exception('Unable to determine navigation template');
        }

        return $this->twig->render($template, [
            'node' => $node,
        ]);
    }
}
