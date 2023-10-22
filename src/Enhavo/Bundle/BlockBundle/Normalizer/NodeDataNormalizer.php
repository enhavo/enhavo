<?php

namespace Enhavo\Bundle\BlockBundle\Normalizer;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ApiBundle\Normalizer\AbstractDataNormalizer;
use Enhavo\Bundle\AppBundle\Template\TemplateResolver;
use Enhavo\Bundle\BlockBundle\Block\BlockManager;
use Enhavo\Bundle\BlockBundle\Model\NodeInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareTrait;

class NodeDataNormalizer extends AbstractDataNormalizer implements NormalizerAwareInterface
{
    use NormalizerAwareTrait;

    public function __construct(
        private readonly BlockManager $blockManager,
        private readonly TemplateResolver $templateResolver,
    )
    {
    }

    public function buildData(Data $data, $object, string $format = null, array $context = [])
    {
        if (!$this->hasSerializationGroup('endpoint', $context)) {
            return;
        }

        /** @var NodeInterface $object */
        $data->set('template', $this->getTemplate($object));

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
                    $nodeData[$key] = $this->normalizer->normalize($viewData, null, ['groups' => 'endpoint']);
                }
            }
        }
        $data->set('data', $nodeData);
    }

    public static function getSupportedTypes(): array
    {
        return [NodeInterface::class];
    }

    private function getTemplate(NodeInterface $node)
    {
        if ($node->getType() === NodeInterface::TYPE_BLOCK && $node->getName()) {
            $block = $this->blockManager->getBlock($node->getName());
            $template = $block->getTemplate();
            return $this->templateResolver->resolve($template);
        }

        return null;
    }
}
