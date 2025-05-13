<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\ResourceBundle\Tests\Duplicate;

use Enhavo\Bundle\ResourceBundle\Duplicate\AbstractDuplicateType;
use Enhavo\Bundle\ResourceBundle\Duplicate\Duplicate;
use Enhavo\Bundle\ResourceBundle\Duplicate\DuplicateFactory;
use Enhavo\Bundle\ResourceBundle\Duplicate\Metadata\Metadata;
use Enhavo\Bundle\ResourceBundle\Duplicate\SourceValue;
use Enhavo\Bundle\ResourceBundle\Duplicate\TargetValue;
use Enhavo\Component\Metadata\MetadataRepository;
use Enhavo\Component\Type\FactoryInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class DuplicateFactoryTest extends TestCase
{
    public function createDependencies()
    {
        $dependencies = new DuplicateFactoryDependencies();
        $dependencies->metadataRepository = $this->getMockBuilder(MetadataRepository::class)->disableOriginalConstructor()->getMock();
        $dependencies->duplicateFactory = $this->getMockBuilder(FactoryInterface::class)->disableOriginalConstructor()->getMock();

        return $dependencies;
    }

    public function createInstance(DuplicateFactoryDependencies $dependencies)
    {
        $instance = new DuplicateFactory(
            $dependencies->metadataRepository,
            $dependencies->duplicateFactory,
        );

        return $instance;
    }

    public function testDuplicate()
    {
        $dependencies = $this->createDependencies();
        $dependencies->metadataRepository->method('getMetadata')->willReturnCallback(function ($source) {
            if (EnhavoEntity::class == $source) {
                $metadata = new Metadata(EnhavoEntity::class);
                $metadata->setProperties([
                    'label' => [
                        ['type' => 'simple'],
                    ],
                ]);

                return $metadata;
            }
            if (AppEntity::class == $source) {
                $metadata = new Metadata(AppEntity::class);
                $metadata->setProperties([
                    'content' => [
                        ['type' => 'simple'],
                    ],
                ]);

                return $metadata;
            }
        });
        $dependencies->duplicateFactory->method('create')->willReturnCallback(function ($config) {
            if ('simple' === $config['type']) {
                return new Duplicate(new SimpleDuplicateType(), [], []);
            }
            throw new \InvalidArgumentException();
        });

        $instance = $this->createInstance($dependencies);

        $source = new AppEntity();
        $source->content = 'MyContent';
        $source->label = 'MyLabel';

        $newSource = $instance->duplicate($source);

        $this->assertFalse($newSource === $source);
        $this->assertEquals('MyContent', $newSource->content);
        $this->assertEquals('MyLabel', $newSource->label);
    }
}

class DuplicateFactoryDependencies
{
    public MetadataRepository|MockObject $metadataRepository;
    public FactoryInterface|MockObject $duplicateFactory;
}

class EnhavoEntity
{
    public ?string $label = null;
}

class AppEntity extends EnhavoEntity
{
    public ?string $content = null;
}

class SimpleDuplicateType extends AbstractDuplicateType
{
    public function duplicate($options, SourceValue $sourceValue, TargetValue $targetValue, $context): void
    {
        $targetValue->setValue($sourceValue->getValue());
    }

    public function isApplicable($options, SourceValue $sourceValue, TargetValue $targetValue, $context): bool
    {
        return true;
    }
}
