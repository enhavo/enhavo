<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2020-06-12
 * Time: 21:51
 */

namespace Enhavo\Bundle\SearchBundle\Tests\Metadata\Provider;

use Enhavo\Bundle\SearchBundle\Metadata\Metadata;
use Enhavo\Bundle\SearchBundle\Metadata\Provider\BundleProvider;
use Enhavo\Component\Metadata\Exception\ProviderException;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpKernel\KernelInterface;

class BundleProviderTest extends TestCase
{
    private function createDependencies()
    {
        $dependencies = new BundleProviderTestDependencies();
        $dependencies->kernel = $this->getMockBuilder(KernelInterface::class)->getMock();
        return $dependencies;
    }

    private function createInstance(BundleProviderTestDependencies $dependencies)
    {
        return new BundleProvider($dependencies->kernel);
    }

    public function testProvide()
    {
        $dependencies = $this->createDependencies();
        $dependencies->kernel->method('getBundles')->willReturn([new MyBundle()]);
        $provider = $this->createInstance($dependencies);

        $metadata = new Metadata(SomeClass::class);
        $provider->provide($metadata, []);

        $this->assertEquals('MyBundle', $metadata->getBundleName());
        $this->assertEquals('my_bundle', $metadata->getHumanizedBundleName());
        $this->assertEquals('SomeClass', $metadata->getEntityName());
    }

    public function testInvalidType()
    {
        $this->expectException(ProviderException::class);
        $dependencies = $this->createDependencies();
        $provider = $this->createInstance($dependencies);
        $provider->provide(new \Enhavo\Component\Metadata\Metadata('SomeClass'), []);
    }
}

class BundleProviderTestDependencies
{
    /** @var KernelInterface|\PHPUnit_Framework_MockObject_MockObject */
    public $kernel;
}

class SomeClass
{

}

class MyBundle
{

}
