<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\ResourceBundle\DependencyInjection\Compiler;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

class ResourceCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        $resources = $container->getParameter('enhavo_resource.resources');
        foreach ($resources as $key => $resource) {
            $container->setParameter(sprintf('%s.model.class', $key), $resource['classes']['model']);
            $container->setParameter(sprintf('%s.repository.class', $key), $resource['classes']['repository']);
            $container->setParameter(sprintf('%s.factory.class', $key), $resource['classes']['factory']);

            $this->addResourceParameter($key, $resource, $container);
            $this->addRepository($key, $resource['classes']['model'], $resource['classes']['repository'], $container);
            $this->addFactory($key, $resource['classes']['model'], $resource['classes']['factory'], $container);
            $this->addRepositoryBindings($key, $resource['classes']['repository'], $container);
            $this->addFactoryBindings($key, $resource['classes']['factory'], $container);
        }
    }

    private function addRepository($name, $modelClass, $repositoryClass, ContainerBuilder $container): void
    {
        $definition = new Definition();
        $definition->setClass($repositoryClass);

        $serviceId = sprintf('%s.repository', $name);

        if ($container->hasDefinition($serviceId) || $container->hasAlias($serviceId)) {
            return;
        }

        if ($this->instanceOf($repositoryClass, ServiceEntityRepository::class, true)) {
            $definition->setArguments([new Reference('doctrine')]);
            $container->setDefinition($serviceId, $definition);
        } elseif ($this->instanceOf($repositoryClass, EntityRepository::class)) {
            $managerReference = new Reference('doctrine');
            $definition->setFactory([$managerReference, 'getRepository']);
            $definition->setArguments([$modelClass]);
            $container->setDefinition($serviceId, $definition);
        }
    }

    private function addFactory($name, $modelClass, $factoryClass, ContainerBuilder $container): void
    {
        $serviceId = sprintf('%s.factory', $name);

        if ($container->hasDefinition($serviceId) || $container->hasAlias($serviceId)) {
            return;
        }

        $definition = new Definition();
        $definition->setClass($factoryClass);
        $definition->setArguments([$modelClass]);
        $container->setDefinition($serviceId, $definition);
    }

    private function instanceOf($instance, $class): bool
    {
        return is_a($instance, $class) || is_subclass_of($instance, $class);
    }

    private function addResourceParameter($key, $resource, ContainerBuilder $container): void
    {
        if (!$container->hasParameter('enhavo_resources.resources')) {
            $container->setParameter('enhavo_resources.resources', []);
        }

        $resources = $container->getParameter('enhavo_resources.resources');
        $resources[$key] = $resource;
        $container->setParameter('enhavo_resources.resources', $resources);
    }

    private function addRepositoryBindings($name, $repositoryClass, ContainerBuilder $container): void
    {
        $names = $this->getNames($name);
        $serviceId = sprintf('%s.repository', $name);
        foreach ($names as $name) {
            $container->registerAliasForArgument($serviceId, $repositoryClass, $name.'_repository');
        }
    }

    private function addFactoryBindings($name, $factoryClass, ContainerBuilder $container): void
    {
        $names = $this->getNames($name);
        $serviceId = sprintf('%s.factory', $name);
        foreach ($names as $name) {
            $container->registerAliasForArgument($serviceId, $factoryClass, $name.'_factory');
        }
    }

    private function getNames(string $name): array
    {
        $parts = explode('.', $name);

        $names = [];
        while ($name = array_pop($parts)) {
            $names[] = count($names) > 0 ? $name.'_'.implode('_', $names) : $name;
        }

        return $names;
    }
}
