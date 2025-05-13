<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\DoctrineExtensionBundle\Tests\Metadata;

use Enhavo\Bundle\DoctrineExtensionBundle\Metadata\Metadata;
use Enhavo\Bundle\DoctrineExtensionBundle\Metadata\Provider\ExtendProvider;
use Enhavo\Component\Metadata\Exception\ProviderException;
use PHPUnit\Framework\TestCase;

class ExtendProviderTest extends TestCase
{
    public function testProvide()
    {
        $metadata = new Metadata('SomeClass');
        $provider = new ExtendProvider();
        $provider->provide($metadata, [
            'extends' => 'ParentClass',
            'discrName' => 'some',
        ]);

        $this->assertEquals('ParentClass', $metadata->getExtends());
        $this->assertEquals('some', $metadata->getDiscrName());
    }

    public function testDiscrNameMissing()
    {
        $this->expectException(ProviderException::class);
        $metadata = new Metadata('SomeClass');
        $provider = new ExtendProvider();
        $provider->provide($metadata, [
            'extends' => 'ParentClass',
        ]);
    }

    public function testExtendsMissing()
    {
        $this->expectException(ProviderException::class);
        $metadata = new Metadata('SomeClass');
        $provider = new ExtendProvider();
        $provider->provide($metadata, [
            'discrName' => 'some',
        ]);
    }
}
