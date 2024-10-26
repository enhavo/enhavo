<?php

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
        $resources = $container->getParameter('enhavo_resources');
        foreach ($resources as $key => $resource)
        {
            $container->setParameter(sprintf('%s.model.class', $key), $resource['classes']['model']);
            $container->setParameter(sprintf('%s.repository.class', $key), $resource['classes']['repository']);
            $container->setParameter(sprintf('%s.factory.class', $key), $resource['classes']['factory']);


            $this->addResourceParameter($key, $resource, $container);
            $this->addRepository($key, $resource['classes']['model'], $resource['classes']['repository'], $container);
            $this->addFactory($key, $resource['classes']['model'], $resource['classes']['factory'], $container);
        }
    }

    protected function addRepository($name, $modelClass, $repositoryClass, ContainerBuilder $container): void
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
        } else if ($this->instanceOf($repositoryClass, EntityRepository::class)) {
            $managerReference = new Reference('doctrine');
            $definition->setFactory([$managerReference, 'getRepository']);
            $definition->setArguments([$modelClass]);
            $container->setDefinition($serviceId, $definition);
        }
    }

    protected function addFactory($name, $modelClass, $factoryClass, ContainerBuilder $container): void
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
}
