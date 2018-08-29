<?php

namespace Enhavo\Bundle\SearchBundle;

use Enhavo\Bundle\AppBundle\DependencyInjection\Compiler\ConfigCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Enhavo\Bundle\AppBundle\Type\TypeCompilerPass;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\DependencyInjection\Definition;
use Doctrine\Bundle\DoctrineBundle\DependencyInjection\Compiler\DoctrineOrmMappingsPass;
use Doctrine\Common\Persistence\Mapping\Driver\DefaultFileLocator;
use Doctrine\ORM\Mapping\Driver\YamlDriver;

class EnhavoSearchBundle extends Bundle
{
    protected $kernel;

    public function __construct(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass($this->buildDoctrineItemCompilerPass(
            'doctrine-database',
            'Enhavo\\Bundle\\SearchBundle\\Model\\Database',
            'enhavo_search.doctrine.enable_database'
        ));

        $container->addCompilerPass(
            new TypeCompilerPass('enhavo_search.extractor_collector', 'enhavo_search.extractor')
        );

        $container->addCompilerPass(
            new TypeCompilerPass('enhavo_search.indexer_collector', 'enhavo_search.indexer')
        );

        $container->addCompilerPass(
            new TypeCompilerPass('enhavo_search.filter_provider_collector', 'enhavo_search.filter_provider')
        );


        $container->addCompilerPass(new ConfigCompilerPass($this->kernel, 'search', 'search_metadata'));
    }

    private function buildDoctrineItemCompilerPass($configDir, $namespace, $enableParameter = false)
    {
        $arguments = array(array(realpath(sprintf('%s/Resources/config/%s', __DIR__, $configDir))), '.orm.yml');
        $locator = new Definition(DefaultFileLocator::class, $arguments);
        $driver = new Definition(YamlDriver::class, array($locator));

        return new DoctrineOrmMappingsPass(
            $driver,
            [$namespace],
            [],
            $enableParameter
        );
    }
}
