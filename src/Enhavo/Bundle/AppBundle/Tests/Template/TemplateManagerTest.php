<?php

namespace Enhavo\Bundle\AppBundle\Tests\Template;

use Enhavo\Bundle\AppBundle\Filesystem\Filesystem;
use Enhavo\Bundle\AppBundle\Template\TemplateManager;
use Enhavo\Bundle\AppBundle\Template\WebpackBuildResolverInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpKernel\KernelInterface;

class TemplateManagerTest extends TestCase
{
    private function createDependencies(): TemplateManagerDependencies
    {
        $dependencies = new TemplateManagerDependencies();
        $dependencies->kernel = $this->getMockBuilder(KernelInterface::class)->getMock();
        $dependencies->fs = new Filesystem();
        $dependencies->resolver = $this->getMockBuilder(WebpackBuildResolverInterface::class)->getMock();
        $dependencies->themePath = __DIR__.'/../Fixtures/template/theme';
        return $dependencies;
    }

    private function createInstance(TemplateManagerDependencies $dependencies)
    {
        $instance = new TemplateManager(
            $dependencies->kernel,
            $dependencies->fs,
            $dependencies->resolver,
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

        self::assertEquals('test', $instance->getTemplate('test'));
    }

    public function testRewritingTemplate()
    {
        $dependencies = $this->createDependencies();
        $instance = $this->createInstance($dependencies);

        $instance->registerPath(__DIR__.'/../Fixtures/template/something', 'MyAlias');

        self::assertEquals('@MyAlias/hello.html.twig', $instance->getTemplate('hello.html.twig'));
        self::assertEquals('@MyAlias/deeper/fubar.html.twig', $instance->getTemplate('deeper/fubar.html.twig'));
        self::assertEquals('else.html.twig', $instance->getTemplate('else.html.twig'));
    }
}

class TemplateManagerDependencies
{
    public KernelInterface|MockObject $kernel;
    public Filesystem|MockObject $fs;
    public WebpackBuildResolverInterface|MockObject $resolver;
    public array $templatePaths = [];
    public string $defaultPath = '';
    public string $themePath = '';
}
