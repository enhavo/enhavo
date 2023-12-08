<?php

namespace Enhavo\Bundle\AppBundle\Normalizer;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ApiBundle\Normalizer\AbstractDataNormalizer;
use Symfony\Component\Routing\RouteCollection;

class RouteCollectionDataNormalizer extends AbstractDataNormalizer
{
    public function buildData(Data $data, $object, string $format = null, array $context = [])
    {
        /** @var $object RouteCollection */
        foreach ($object as $name => $route) {
            $compiledRoute = $route->compile();

            $defaults = array_intersect_key(
                $route->getDefaults(),
                array_fill_keys($compiledRoute->getVariables(), null)
            );

            $data->set($name, [
                'path' => $route->getPath(),
                'defaults' => $defaults,
                'requirements' => $route->getRequirements(),
                'methods' => $route->getMethods(),
            ]);
        }
    }

    public function getSerializationGroups(array $groups, array $context = []): ?array
    {
        if (in_array('endpoint', $groups)) {
            return null; // prevent pre normalization
        }

        return $groups;
    }

    public static function getSupportedTypes(): array
    {
        return [RouteCollection::class];
    }
}
