<?php

namespace Enhavo\Bundle\ContentBundle\Normalizer;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ApiBundle\Normalizer\AbstractDataNormalizer;
use Enhavo\Bundle\ApiBundle\Normalizer\DataNormalizerInterface;
use Enhavo\Bundle\ContentBundle\Entity\Content;
use Enhavo\Bundle\RoutingBundle\Router\Router;

class ContentDataNormalizer extends AbstractDataNormalizer
{
    public function __construct(
        private Router $router,
    )
    {
    }

    public function buildData(Data $data, $object, string $format = null, array $context = [])
    {
        if (!$this->hasSerializationGroup(['endpoint', 'endpoint.navigation'], $context)) {
            return;
        }

        $data->set('url', $this->router->generate($object));
    }

    public static function getSupportedTypes(): array
    {
        return [Content::class];
    }
}
