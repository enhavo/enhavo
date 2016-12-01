<?php

namespace Enhavo\Bundle\TranslationBundle;

use Enhavo\Bundle\TranslationBundle\DependencyInjection\Compiler\RouteCompilerPass;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\KernelInterface;
use Enhavo\Bundle\TranslationBundle\DependencyInjection\Compiler\ConfigCompilerPass;

class EnhavoTranslationBundle extends Bundle
{
    protected $kernel;

    public function __construct(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }
    
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new ConfigCompilerPass($this->kernel));
        $container->addCompilerPass(new RouteCompilerPass());
    }
}