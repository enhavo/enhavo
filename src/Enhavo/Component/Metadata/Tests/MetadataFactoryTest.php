<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Component\Metadata\Tests;

use Enhavo\Component\Metadata\DriverInterface;
use Enhavo\Component\Metadata\Metadata;
use Enhavo\Component\Metadata\MetadataFactory;
use Enhavo\Component\Metadata\ProviderInterface;
use PHPUnit\Framework\TestCase;

class MetadataFactoryTest extends TestCase
{
    private function createDependencies()
    {
        $dependencies = new MetadataFactoryDependencies();
        $dependencies->metaDataClass = MetadataFactoryMetadata::class;

        return $dependencies;
    }

    private function createInstance(MetadataFactoryDependencies $dependencies)
    {
        return new MetadataFactory($dependencies->metaDataClass);
    }

    public function testProviders()
    {
        $dependencies = $this->createDependencies();
        $factory = $this->createInstance($dependencies);

        $provider = new MetadataFactoryNameProvider();
        $factory->addProvider($provider);

        $this->assertCount(1, $factory->getProviders());
        $this->assertTrue($provider === $factory->getProviders()[0]);

        $factory->removeProvider($provider);
        $this->assertCount(0, $factory->getProviders());
    }

    public function testDrivers()
    {
        $dependencies = $this->createDependencies();
        $factory = $this->createInstance($dependencies);

        $driver = new MetadataFactoryDriver();
        $factory->addDriver($driver);

        $this->assertCount(1, $factory->getDrivers());
        $this->assertTrue($driver === $factory->getDrivers()[0]);

        $factory->removeDriver($driver);
        $this->assertCount(0, $factory->getDrivers());
    }

    public function testCreateMetadata()
    {
        $dependencies = $this->createDependencies();
        $factory = $this->createInstance($dependencies);

        $factory->addDriver(new MetadataFactoryDriver());

        $metadata = $factory->createMetadata(MetadataFactoryResource::class);
        $this->assertEquals(MetadataFactoryResource::class, $metadata->getClassName());

        $metadata = $factory->createMetadata(MetadataFactoryOtherResource::class);
        $this->assertNull($metadata);
    }

    public function testCreateMetadataWithForce()
    {
        $dependencies = $this->createDependencies();
        $factory = $this->createInstance($dependencies);

        $factory->addDriver(new MetadataFactoryDriver());

        $metadata = $factory->createMetadata(MetadataFactoryOtherResource::class, true);
        $this->assertEquals(MetadataFactoryOtherResource::class, $metadata->getClassName());
    }

    public function testLoadMetadata()
    {
        $dependencies = $this->createDependencies();
        $factory = $this->createInstance($dependencies);

        $factory->addDriver(new MetadataFactoryDriver());
        $factory->addProvider(new MetadataFactoryNameProvider());

        $metadata = new MetadataFactoryMetadata(MetadataFactoryResource::class);
        $factory->loadMetadata(MetadataFactoryResource::class, $metadata);

        $this->assertEquals(['Peter'], $metadata->names);
    }

    public function testGetAllClasses()
    {
        $dependencies = $this->createDependencies();
        $factory = $this->createInstance($dependencies);
        $factory->addDriver(new MetadataFactoryDriver());
        $this->assertEquals([MetadataFactoryResource::class], $factory->getAllClasses());
    }
}

class MetadataFactoryDependencies
{
    public $metaDataClass;
}

class MetadataFactoryMetadata extends Metadata
{
    public $names = [];
}

class MetadataFactoryResource
{
}

class MetadataFactoryOtherResource
{
}

class MetadataFactoryNameProvider implements ProviderInterface
{
    public function provide(Metadata $metadata, $normalizedData)
    {
        if ($metadata instanceof MetadataFactoryMetadata) {
            if (array_key_exists('names', $normalizedData)) {
                foreach ($normalizedData['names'] as $name) {
                    $metadata->names[] = $name;
                }
            }
        }
    }
}

class MetadataFactoryDriver implements DriverInterface
{
    public function getAllClasses(): array
    {
        return [MetadataFactoryResource::class];
    }

    public function load()
    {
    }

    public function loadClass($className): array|false|null
    {
        if (MetadataFactoryResource::class === $className) {
            return [
                'names' => ['Peter'],
            ];
        }

        return false;
    }
}
