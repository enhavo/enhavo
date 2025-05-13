<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\BlockBundle\Normalizer;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ApiBundle\Normalizer\AbstractDataNormalizer;
use Enhavo\Bundle\AppBundle\Template\TemplateResolver;
use Enhavo\Bundle\BlockBundle\Block\BlockManager;
use Enhavo\Bundle\BlockBundle\Model\NodeInterface;

class NodeDataNormalizer extends AbstractDataNormalizer
{
    public function __construct(
        private readonly BlockManager $blockManager,
        private readonly TemplateResolver $templateResolver,
    ) {
    }

    public function buildData(Data $data, $object, ?string $format = null, array $context = [])
    {
        if (!$this->hasSerializationGroup(['endpoint', 'endpoint.block'], $context)) {
            return;
        }

        /* @var NodeInterface $object */
        $data->set('template', $this->getTemplate($object));
        $data->set('component', $this->getComponent($object));

        $children = [];
        foreach ($object->getChildren() as $child) {
            if (NodeInterface::TYPE_BLOCK === $child->getType()) {
                $children[] = $this->normalizer->normalize($child, null, ['groups' => 'endpoint']);
            }
        }
        $data->set('children', $children);

        $nodeData = [];
        if (NodeInterface::TYPE_BLOCK === $object->getType()) {
            if (empty($object->getViewData())) {
                $this->blockManager->createViewData($object, $object->getBlock() ? $this->blockManager->findRootResource($object->getBlock()) : null);
            }

            if ($object->getViewData()) {
                foreach ($object->getViewData() as $key => $viewData) {
                    $nodeData[$key] = $this->normalizer->normalize($viewData, null, ['groups' => 'endpoint.block']);
                }
            }
        }
        $data->set('data', $nodeData);
    }

    public static function getSupportedTypes(): array
    {
        return [NodeInterface::class];
    }

    public function getSerializationGroups(array $groups, array $context = []): ?array
    {
        if (!$this->hasSerializationGroup(['endpoint', 'endpoint.block'], $context)) {
            return $groups;
        }

        return ['endpoint.block'];
    }

    private function getTemplate(NodeInterface $node): ?string
    {
        if (NodeInterface::TYPE_BLOCK === $node->getType() && $node->getName()) {
            $block = $this->blockManager->getBlock($node->getName());
            $template = $block->getTemplate();

            return $this->templateResolver->resolve($template);
        }

        return null;
    }

    private function getComponent(NodeInterface $node): ?string
    {
        if (NodeInterface::TYPE_BLOCK === $node->getType() && $node->getName()) {
            $block = $this->blockManager->getBlock($node->getName());

            return $block->getComponent();
        }

        return null;
    }
}
