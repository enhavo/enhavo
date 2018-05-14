<?php

namespace Enhavo\Bundle\SearchBundle;

use Enhavo\Bundle\AppBundle\DependencyInjection\Compiler\ConfigCompilerPass;
use Enhavo\Bundle\SearchBundle\Metadata\MetadataCollector;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Enhavo\Bundle\AppBundle\Type\TypeCompilerPass;
use Symfony\Component\HttpKernel\Bundle\Bundle;
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
//        $container->addCompilerPass(
//            new TypeCompilerPass('enhavo_search.index_collector', 'enhavo_search.index')
//        );

        $container->addCompilerPass(
            new TypeCompilerPass('enhavo_search.extractor_collector', 'enhavo_search.extractor')
        );

        $container->addCompilerPass(
            new TypeCompilerPass('enhavo_search.indexer_collector', 'enhavo_search.indexer')
        );

        $container->addCompilerPass(new ConfigCompilerPass($this->kernel, 'search', 'search_metadata'));
    }
}
