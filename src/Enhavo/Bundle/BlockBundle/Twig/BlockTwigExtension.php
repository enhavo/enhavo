<?php

namespace Enhavo\Bundle\BlockBundle\Twig;

use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class BlockTwigExtension extends AbstractExtension
{
    private ?Environment $twig = null;

    public function __construct(
        private readonly array $renderSets
    ) {}
    public function getFunctions(): array
    {
        return array(
            new TwigFunction('block_render', [$this, 'render'], ['is_safe' => ['html']]),
        );
    }

    public function setTwig(Environment $twig): void
    {
        $this->twig = $twig;
    }

    public function render(array $node, string|array|null $renderSet = [], array $onlyRenderTypes = []): string
    {
        if (!$node || $node == []) {
            return '';
        }

        if ($node['type'] === 'block' && (empty($onlyRenderTypes) || in_array($node['name'], $onlyRenderTypes))) {
            return $this->twig->render($this->getTemplate($node, $renderSet), $node['data']);
        }

        if (count($node['children'])) {
            $result = [];
            foreach($node['children'] as $child) {
                $result[] = $this->render($child, $renderSet, $onlyRenderTypes);
            }
            return join('', $result);
        }

        return '';
    }

    private function getTemplate(array $node, string|array|null $renderSet): string
    {
        if (is_string($renderSet)) {
            if (array_key_exists($renderSet, $this->renderSets) && isset($this->renderSets[$renderSet][$node['name']])) {
                return $this->renderSets[$renderSet][$node['name']];
            }
        } else if (is_array($renderSet)) {
            if (isset($renderSet[$node['name']])) {
                return $renderSet[$node['name']];
            }
        }

        if (empty($node['template'])) {
            throw new \Exception(sprintf('Can\' render block "%s", because the template is empty. Please set the template in the admin', $node['name']));
        }

        return $node['template'];
    }
}
