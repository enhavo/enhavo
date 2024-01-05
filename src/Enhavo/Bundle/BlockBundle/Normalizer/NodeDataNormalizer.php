<?php

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
    )
    {
    }

    public function buildData(Data $data, $object, string $format = null, array $context = [])
    {
        if (!$this->hasSerializationGroup(['endpoint', 'endpoint.block'], $context)) {
            return;
        }

        /** @var NodeInterface $object */
        $data->set('template', $this->getTemplate($object));
        $data->set('component', $this->getComponent($object));

        $children = [];
        foreach ($object->getChildren() as $child) {
            if ($child->getType() === NodeInterface::TYPE_BLOCK) {
                $children[] = $this->normalizer->normalize($child, null, ['groups' => 'endpoint']);
            }
        }
        $data->set('children', $children);

        $nodeData = [];
        if ($object->getType() === NodeInterface::TYPE_BLOCK) {
            if (empty($object->getViewData())) {
                $this->blockManager->createViewData($object, $this->blockManager->findRootResource($object->getBlock()));
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
        if ($node->getType() === NodeInterface::TYPE_BLOCK && $node->getName()) {
            $block = $this->blockManager->getBlock($node->getName());
            $template = $block->getTemplate();
            return $this->templateResolver->resolve($template);
        }

        return null;
    }

    private function getComponent(NodeInterface $node): ?string
    {
        if ($node->getType() === NodeInterface::TYPE_BLOCK && $node->getName()) {
            $block = $this->blockManager->getBlock($node->getName());
            return $block->getComponent();
        }

        return null;
    }
}
