<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2020-06-10
 * Time: 13:03
 */

namespace Enhavo\Component\Metadata;


class MetadataFactory
{
    /** @var DriverInterface[] */
    private array $drivers = [];

    /** @var ProviderInterface[] */
    private array $providers = [];

    /** @var Metadata[] */
    private array $metadata = [];

    private array $loadedData = [];

    private ?array $classes = null;

    public function __construct(
        private readonly string $metaDataClass
    )
    {
    }

    /** @retrun string[] */
    public function getAllClasses(): array
    {
        if ($this->classes !== null) {
            return $this->classes;
        }
        $this->classes = [];
        foreach ($this->drivers as $driver) {
            foreach ($driver->getAllClasses() as $class) {
                if (!in_array($class, $this->classes)) {
                    $this->classes[] = $class;
                }
            }
        }

        return $this->classes;
    }

    public function createMetadata($className, bool $force = false): ?Metadata
    {
        if (array_key_exists($className, $this->metadata)) {
            return $this->metadata[$className];
        }

        $metadata = new $this->metaDataClass($className);

        $hasMetadata = $this->loadMetadata($className, $metadata);

        $this->metadata[$className] = $hasMetadata || $force ? $metadata : null;
        return $this->metadata[$className];
    }

    public function loadMetadata($className, Metadata $metadata): bool
    {
        if (!array_key_exists($className, $this->loadedData)) {
            $this->loadedData[$className] = [];

            foreach ($this->drivers as $driver) {
                $this->loadedData[$className][] = $driver->loadClass($className);
            }
        }

        $hasMetadata = false;
        foreach ($this->loadedData[$className] as $normalizedData) {
            if ($normalizedData !== false) {
                $hasMetadata = true;
            }
            foreach ($this->providers as $provider) {
                $provider->provide($metadata, is_array($normalizedData) ? $normalizedData : []);
            }
        }

        return $hasMetadata;
    }

    public function addProvider(ProviderInterface $provider): void
    {
        $this->providers[] = $provider;
    }


    public function removeProvider(ProviderInterface $provider): void
    {
        if (false !== $key = array_search($provider, $this->providers, true)) {
            array_splice($this->providers, $key, 1);
        }
    }

    public function addDriver(DriverInterface $driver): void
    {
        $this->drivers[] = $driver;
    }

    public function removeDriver(DriverInterface $driver): void
    {
        if (false !== $key = array_search($driver, $this->drivers, true)) {
            array_splice($this->drivers, $key, 1);
        }
    }

    public function getDrivers(): array
    {
        return $this->drivers;
    }

    public function getProviders(): array
    {
        return $this->providers;
    }
}
