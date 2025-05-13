<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\SearchBundle;

use Doctrine\Bundle\DoctrineBundle\DependencyInjection\Compiler\DoctrineOrmMappingsPass;
use Doctrine\ORM\Mapping\Driver\XmlDriver;
use Doctrine\Persistence\Mapping\Driver\DefaultFileLocator;
use Enhavo\Bundle\SearchBundle\DependencyInjection\Compiler\SearchEngineCompilerPass;
use Enhavo\Bundle\SearchBundle\Filter\Filter;
use Enhavo\Bundle\SearchBundle\Filter\FilterTypeInterface;
use Enhavo\Bundle\SearchBundle\Index\Index;
use Enhavo\Bundle\SearchBundle\Index\IndexTypeInterface;
use Enhavo\Component\Type\TypeCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class EnhavoSearchBundle extends Bundle
{
    public function build(ContainerBuilder $container): void
    {
        $container->addCompilerPass($this->buildDoctrineItemCompilerPass());
        $container->addCompilerPass(new TypeCompilerPass('SearchFilter', 'enhavo_search.filter', Filter::class));
        $container->addCompilerPass(new TypeCompilerPass('SearchIndex', 'enhavo_search.index', Index::class));
        $container->addCompilerPass(new SearchEngineCompilerPass());

        $container->registerForAutoconfiguration(IndexTypeInterface::class)->addTag('enhavo_search.index');
        $container->registerForAutoconfiguration(FilterTypeInterface::class)->addTag('enhavo_search.filter');
    }

    private function buildDoctrineItemCompilerPass(): DoctrineOrmMappingsPass
    {
        $arguments = [[realpath(sprintf('%s/Resources/config/doctrine-database', __DIR__))], '.orm.xml'];
        $locator = new Definition(DefaultFileLocator::class, $arguments);
        $driver = new Definition(XmlDriver::class, [$locator]);

        return new DoctrineOrmMappingsPass(
            $driver,
            ['Enhavo\\Bundle\\SearchBundle\\Model\\Database'],
            [],
            'enhavo_search.doctrine.enable_database'
        );
    }
}
