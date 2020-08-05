<?php


namespace Enhavo\Bundle\BlockBundle\Renderer;


use Enhavo\Bundle\BlockBundle\Model\NodeInterface;

interface BlockRendererInterface
{
    public function render(NodeInterface $node, $resource, $templateSet, $onlyRenderTypes);
}
