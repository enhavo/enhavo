<?php

namespace Enhavo\Bundle\SearchBundle;

use Doctrine\ORM\Mapping\Driver\XmlDriver;
use Enhavo\Bundle\SearchBundle\DependencyInjection\Compiler\SearchEngineCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Enhavo\Bundle\AppBundle\Type\TypeCompilerPass;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\Definition;
use Doctrine\Bundle\DoctrineBundle\DependencyInjection\Compiler\DoctrineOrmMappingsPass;
use Doctrine\Persistence\Mapping\Driver\DefaultFileLocator;

class EnhavoSearchBundle extends Bundle
{
    public function build(ContainerBuilder $container): void
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

        $container->addCompilerPass(new SearchEngineCompilerPass());
    }

    private function buildDoctrineItemCompilerPass($configDir, $namespace, $enableParameter = false)
    {
        $arguments = array(array(realpath(sprintf('%s/Resources/config/%s', __DIR__, $configDir))), '.orm.xml');
        $locator = new Definition(DefaultFileLocator::class, $arguments);
        $driver = new Definition(XmlDriver::class, array($locator));

        return new DoctrineOrmMappingsPass(
            $driver,
            [$namespace],
            [],
            $enableParameter
        );
    }
}
