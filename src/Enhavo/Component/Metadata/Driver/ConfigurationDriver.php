<?php
/**
 * MetadataCollector.php
 *
 * @since 10/05/18
 * @author gseidel
 */

namespace Enhavo\Component\Metadata\Driver;

use Enhavo\Component\Metadata\DriverInterface;

class ConfigurationDriver implements DriverInterface
{
    public function __construct(
        private readonly array $configuration
    )
    {
    }

    public function loadClass($className): array|null|false
    {
        if (array_key_exists($className, $this->configuration)) {
            return $this->configuration[$className];
        }

        return false;
    }

    public function getAllClasses(): array
    {
        return array_keys($this->configuration);
    }
}
