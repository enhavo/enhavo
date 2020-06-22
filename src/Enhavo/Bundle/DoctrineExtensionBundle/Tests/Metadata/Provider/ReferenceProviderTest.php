<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2020-06-12
 * Time: 12:34
 */

namespace Enhavo\Bundle\DoctrineExtensionBundle\Tests\Metadata;

use Enhavo\Bundle\DoctrineExtensionBundle\Metadata\Metadata;
use Enhavo\Bundle\DoctrineExtensionBundle\Metadata\Provider\ReferenceProvider;
use Enhavo\Component\Metadata\Exception\ProviderException;
use PHPUnit\Framework\TestCase;

class ReferenceProviderTest extends TestCase
{
    public function testProvide()
    {
        $metadata = new Metadata('SomeClass');
        $provider = new ReferenceProvider();
        $provider->provide($metadata, [
            'reference' => [
                'name' => [
                    'idField' => 'entityId',
                    'nameField' => 'entityName'
                ]
            ]
        ]);

        $this->assertCount(1, $metadata->getReferences());
        $this->assertEquals('entityId', $metadata->getReferences()[0]->getIdField());
        $this->assertEquals('entityName', $metadata->getReferences()[0]->getNameField());
        $this->assertEquals('name', $metadata->getReferences()[0]->getProperty());
    }

    public function testIdFieldMissing()
    {
        $this->expectException(ProviderException::class);
        $metadata = new Metadata('SomeClass');
        $provider = new ReferenceProvider();
        $provider->provide($metadata, [
            'reference' => [
                'name' => [
                    'nameField' => 'entityName'
                ]
            ]
        ]);
    }

    public function testNameFieldMissing()
    {
        $this->expectException(ProviderException::class);
        $metadata = new Metadata('SomeClass');
        $provider = new ReferenceProvider();
        $provider->provide($metadata, [
            'reference' => [
                'name' => [
                    'idField' => 'entityId',
                ]
            ]
        ]);
    }

    public function testConfigNotArray()
    {
        $this->expectException(ProviderException::class);
        $metadata = new Metadata('SomeClass');
        $provider = new ReferenceProvider();
        $provider->provide($metadata, [
            'reference' => [
                'name' => 'something'
            ]
        ]);
    }
}
