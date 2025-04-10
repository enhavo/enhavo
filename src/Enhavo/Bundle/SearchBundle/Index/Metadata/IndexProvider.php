<?php

namespace Enhavo\Bundle\SearchBundle\Index\Metadata;

use Enhavo\Component\Metadata\Extension\Config;
use Enhavo\Component\Metadata\Metadata as BaseMetadata;
use Enhavo\Component\Metadata\ProviderInterface;

class IndexProvider implements ProviderInterface
{
    public function provide(BaseMetadata $metadata, $normalizedData): void
    {
        if (!$metadata instanceof Metadata) {
            return;
        }

        $properties = $metadata->getIndex();

        $normalizedProperties = $normalizedData['index'] ?? [];
        foreach ($normalizedProperties as $propertyName => $config) {
            $configArray = is_int(array_keys($config)[0]) ? $config : [$config];
            foreach ($configArray as $value) {
                if (!array_key_exists($propertyName, $properties)) {
                    $properties[$propertyName] = [];
                }

                if (!$this->configExists($properties[$propertyName], $value)) {
                    $properties[$propertyName][] = new Config($propertyName, $value);
                }
            }
        }

        $metadata->setIndex($properties);
    }

    private function configExists($configs, $config): bool
    {
        foreach ($configs as $checkConfig) {
            if ($config === $checkConfig) {
                return true;
            }
        }
        return false;
    }
}
