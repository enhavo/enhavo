<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2020-06-09
 * Time: 17:25
 */

namespace Enhavo\Component\DoctrineExtension\Mapping;

use Doctrine\Persistence\Mapping\ClassMetadata;
use Doctrine\Persistence\Mapping\Driver\MappingDriverChain;
use Doctrine\Persistence\Mapping\Driver\MappingDriver;

class MappingExtensionDriver extends MappingDriverChain
{
    /** @var ExtensionRegistry */
    private $registry;

    /** @var string[] */
    private $classMap = [];

    public function __construct(MappingDriver $mappingDriver, ExtensionRegistry $registry)
    {
        $this->setDefaultDriver($mappingDriver);
        $this->registry = $registry;
        foreach($this->registry->getExtensions() as $extension) {
            $extension->loadDriver($mappingDriver);
        }
    }

    public function loadMetadataForClass($className, ClassMetadata $metadata): void
    {
        if(!isset($this->classMap[$className])) {
            $this->classMap[$className] = true;
            $this->loadClass($className, $this);
        }
        parent::loadMetadataForClass($className, $metadata);
    }

    private function loadClass($className, MappingDriver $driver)
    {
        if($driver instanceof MappingDriverChain) {
            if($driver->getDefaultDriver()) {
                $this->loadClass($className, $driver->getDefaultDriver());
            }

            foreach($driver->getDrivers() as $class => $driver) {
                $this->loadClass($className, $driver);
            }
        } else {
            if(in_array($className, $driver->getAllClassNames())) {
                foreach($this->registry->getExtensions() as $extension) {
                    $extension->loadClass($className, $driver);
                }
            }
        }
    }
}
