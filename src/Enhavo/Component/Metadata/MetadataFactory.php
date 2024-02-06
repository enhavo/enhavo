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
    /** @var  */
    private $metaDataClass;

    /** @var DriverInterface[] */
    private $drivers;

    /** @var ProviderInterface[] */
    private $providers;

    /** @var array */
    private $configurations = [];

    /** @var bool  */
    private $loaded = false;

    /**
     * MetadataFactory constructor.
     * @param $metaDataClass
     */
    public function __construct($metaDataClass)
    {
        $this->metaDataClass = $metaDataClass;
    }

    public function getAllClasses()
    {
        $this->load();

        return array_keys($this->configurations);
    }

    public function createMetadata($className, $force = false): ?Metadata
    {
        $this->load();

        if($force === false && !array_key_exists($className, $this->configurations)) {
            return null;
        }

        $metadata = new $this->metaDataClass($className);
        return $metadata;
    }

    public function loadMetadata($className, Metadata $metadata): bool
    {
        $this->load();

        if (array_key_exists($className, $this->configurations)) {
            foreach ($this->providers as $provider) {
                $provider->provide($metadata, $this->configurations[$className]);
            }
            return true;
        }
        return false;
    }

    private function load()
    {
        if($this->loaded) {
            return;
        }

        foreach($this->drivers as $driver) {
            $driver->load();
            $data = $driver->getNormalizedData();
            foreach($data as $className => $configuration) {
                if(array_key_exists($className, $this->configurations)) {
                    $this->configurations[$className] = array_merge($this->configurations[$className], $configuration);
                } else {
                    $this->configurations[$className] = $configuration;
                }
            }
        }

        $this->loaded = true;
    }

    /**
     * @param ProviderInterface $provider
     */
    public function addProvider(ProviderInterface $provider)
    {
        $this->providers[] = $provider;
    }

    /**
     * @param ProviderInterface $provider
     */
    public function removeProvider(ProviderInterface $provider)
    {
        if (false !== $key = array_search($provider, $this->providers, true)) {
            array_splice($this->providers, $key, 1);
        }
    }

    /**
     * @param DriverInterface $driver
     */
    public function addDriver(DriverInterface $driver)
    {
        $this->drivers[] = $driver;
    }

    /**
     * @param DriverInterface $driver
     */
    public function removeDriver(DriverInterface $driver)
    {
        if (false !== $key = array_search($driver, $this->drivers, true)) {
            array_splice($this->drivers, $key, 1);
        }
    }

    /**
     * @return DriverInterface[]
     */
    public function getDrivers(): array
    {
        return $this->drivers;
    }

    /**
     * @return ProviderInterface[]
     */
    public function getProviders(): array
    {
        return $this->providers;
    }
}
