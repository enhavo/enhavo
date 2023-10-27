<?php

namespace Enhavo\Bundle\NavigationBundle\Normalizer;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ApiBundle\Normalizer\AbstractDataNormalizer;
use Enhavo\Bundle\NavigationBundle\Model\NodeInterface;
use Enhavo\Bundle\NavigationBundle\Navigation\NavigationManager;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareTrait;

class NavigationNodeNormalizer extends AbstractDataNormalizer implements NormalizerAwareInterface
{
    use NormalizerAwareTrait;

    public function __construct(
        private NavigationManager $navigationManager,
    ) {}

    public function buildData(Data $data, $object, string $format = null, array $context = [])
    {
        if (!$this->hasSerializationGroup('endpoint', $context)) {
            return;
        }

        $data->set('active', $this->navigationManager->isActive($object));
    }

    public static function getSupportedTypes(): array
    {
        return [NodeInterface::class];
    }
}
