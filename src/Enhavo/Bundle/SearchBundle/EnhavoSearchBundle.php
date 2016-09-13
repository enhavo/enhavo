<?php

namespace Enhavo\Bundle\SearchBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Enhavo\Bundle\AppBundle\Type\TypeCompilerPass;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Enhavo\Bundle\SearchBundle\DependencyInjection\Compiler\ConfigCompilerPass;
use Symfony\Component\HttpKernel\KernelInterface;

class EnhavoSearchBundle extends Bundle
{
    protected $kernel;

    public function __construct(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(
            new TypeCompilerPass('enhavo_search.index_collector', 'enhavo_search.index')
        );
        $container->addCompilerPass(new ConfigCompilerPass($this->kernel));
    }
}
