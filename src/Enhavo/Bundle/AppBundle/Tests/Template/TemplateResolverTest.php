<?php

namespace Enhavo\Bundle\AppBundle\Tests\Template;

use Enhavo\Bundle\AppBundle\Filesystem\Filesystem;
use Enhavo\Bundle\AppBundle\Template\TemplateResolver;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpKernel\KernelInterface;

class TemplateResolverTest extends TestCase
{
    private function createDependencies(): TemplateResolverDependencies
    {
        $dependencies = new TemplateResolverDependencies();
        $dependencies->kernel = $this->getMockBuilder(KernelInterface::class)->getMock();
        $dependencies->fs = new Filesystem();
        $dependencies->themePath = __DIR__.'/../Fixtures/template/theme';
        return $dependencies;
    }

    private function createInstance(TemplateResolverDependencies $dependencies)
    {
        $instance = new TemplateResolver(
            $dependencies->kernel,
            $dependencies->fs,
            $dependencies->templatePaths,
            $dependencies->defaultPath,
            $dependencies->themePath,
        );

        return $instance;
    }

    public function testIfTemplateNotFoundTemplate()
    {
        $dependencies = $this->createDependencies();
        $instance = $this->createInstance($dependencies);

        self::assertEquals('test', $instance->resolve('test'));
    }

    public function testRewritingTemplate()
    {
        $dependencies = $this->createDependencies();
        $instance = $this->createInstance($dependencies);

        $instance->registerPath(__DIR__.'/../Fixtures/template/something', 'MyAlias');

        self::assertEquals('@MyAlias/hello.html.twig', $instance->resolve('hello.html.twig'));
        self::assertEquals('@MyAlias/deeper/fubar.html.twig', $instance->resolve('deeper/fubar.html.twig'));
        self::assertEquals('else.html.twig', $instance->resolve('else.html.twig'));
    }
}

class TemplateResolverDependencies
{
    public KernelInterface|MockObject $kernel;
    public Filesystem|MockObject $fs;
    public array $templatePaths = [];
    public string $defaultPath = '';
    public string $themePath = '';
}
