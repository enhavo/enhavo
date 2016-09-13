<?php

namespace Enhavo\Bundle\SearchBundle\Tests\Metadata;

use Enhavo\Bundle\SearchBundle\Metadata\MetadataCollector;
use Enhavo\Bundle\SearchBundle\Tests\Mock\SplFileInfoMock;
use Enhavo\Bundle\SearchBundle\EnhavoSearchBundle;

/**
 * MetadataFactory.php
 *
 * @since 23/06/16
 * @author gseidel
 */
class MetadataCollectorTest extends \PHPUnit_Framework_TestCase
{
    public function testGetConfigurationsForSingleFile()
    {
        $kernelMock = $this->getMockBuilder('Symfony\Component\HttpKernel\KernelInterface')->getMock();

        $kernelMock->method('getBundles')->willReturn([
            new EnhavoSearchBundle($kernelMock)
        ]);

        $filesystemMock = $this->getMockBuilder('Enhavo\Bundle\AppBundle\Filesystem\Filesystem')->getMock();
        $filesystemMock->method('readFile')->willReturn(
            json_encode([
                'ResourceEntity1' => [
                    'type' => 'myType',
                    'option' => 'Option'
                ],
                'ResourceEntity2' => [
                    'type' => 'myType'
                ]
            ])
        );

        $metadataCollector = new MetadataCollector($kernelMock, $filesystemMock);

        $configuration = $metadataCollector->getConfigurations();

        $this->assertCount(2, $configuration);
        $this->assertArrayHasKey('ResourceEntity1', $configuration);

        $this->assertCount(2, $configuration['ResourceEntity1']);
        $this->assertArrayHasKey('type', $configuration['ResourceEntity1']);
        $this->assertArrayHasKey('option', $configuration['ResourceEntity1']);

        $this->assertArrayHasKey('ResourceEntity2', $configuration);

        $this->assertCount(1, $configuration['ResourceEntity2']);
        $this->assertArrayHasKey('type', $configuration['ResourceEntity2']);

        $metadataCollector->getConfigurations();
    }

    public function testCachingConfigurations()
    {
        $kernelMock = $this->getMockBuilder('Symfony\Component\HttpKernel\KernelInterface')->getMock();

        $kernelMock->method('getBundles')->willReturn([
            new EnhavoSearchBundle($kernelMock)
        ]);

        $filesystemMock = $this->getMockBuilder('Enhavo\Bundle\AppBundle\Filesystem\Filesystem')->getMock();
        $filesystemMock->expects($this->once())->method('readFile')->willReturn(
            json_encode([
                'ResourceEntity1' => [
                    'type' => 'myType',
                    'option' => 'Option'
                ],
                'ResourceEntity2' => [
                    'type' => 'myType'
                ]
            ])
        );

        $metadataCollector = new MetadataCollector($kernelMock, $filesystemMock);

        $metadataCollector->getConfigurations();
        $metadataCollector->getConfigurations();
    }
}