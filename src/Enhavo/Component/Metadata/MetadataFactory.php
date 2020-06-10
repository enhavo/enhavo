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

    public function createMetadata($className)
    {
        if($this->loaded = false) {
            $this->load();
        }

        if(array_key_exists($className, $this->configurations)) {
            return null;
        }

        $metadata = new $this->metaDataClass($className);
        return $metadata;
    }

    public function loadMetadata(Metadata $metadata)
    {
        if($this->loaded = false) {
            $this->load();
        }

        if(array_key_exists($metadata->getClassName(), $this->configurations)) {
            foreach($this->providers as $provider) {
                $provider->provide($metadata, $this->configurations[$metadata->getClassName()]);
            }
        }
    }

    private function load()
    {
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
}
