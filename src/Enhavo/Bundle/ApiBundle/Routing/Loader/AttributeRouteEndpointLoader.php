<?php

namespace Enhavo\Bundle\ApiBundle\Routing\Loader;

use Symfony\Component\Config\Resource\FileResource;
use Symfony\Component\Routing\Loader\AttributeClassLoader;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

class AttributeRouteEndpointLoader extends AttributeClassLoader
{
    public function load($class, string $type = null): RouteCollection
    {
        if (!class_exists($class)) {
            throw new \InvalidArgumentException(sprintf('Class "%s" does not exist.', $class));
        }

        $class = new \ReflectionClass($class);
        if ($class->isAbstract()) {
            throw new \InvalidArgumentException(sprintf('Annotations from class "%s" cannot be read as it is abstract.', $class->getName()));
        }

        $globals = $this->getGlobals($class);

        $collection = new RouteCollection();
        $collection->addResource(new FileResource($class->getFileName()));

        if ($globals['env'] && $this->env !== $globals['env']) {
            return $collection;
        }

        $globals = $this->resetGlobals();
        foreach ($this->getAnnotations($class) as $annot) {
            $this->addRoute($collection, $annot, $globals, $class, $class->getMethod('handleRequest'));
        }

        return $collection;
    }

    private function resetGlobals(): array
    {
        return [
            'path' => null,
            'localized_paths' => [],
            'requirements' => [],
            'options' => [],
            'defaults' => [],
            'schemes' => [],
            'methods' => [],
            'host' => '',
            'condition' => '',
            'name' => '',
            'priority' => 0,
            'env' => null,
        ];
    }

    private function getAnnotations(object $reflection): iterable
    {
        foreach ($reflection->getAttributes($this->routeAnnotationClass, \ReflectionAttribute::IS_INSTANCEOF) as $attribute) {
            yield $attribute->newInstance();
        }
    }

    protected function configureRoute(Route $route, \ReflectionClass $class, \ReflectionMethod $method, object $annot): void
    {
        $route->setDefault('_endpoint', [
            'type' => $class->getName()
        ]);
    }

    protected function getDefaultRouteName(\ReflectionClass $class, \ReflectionMethod $method): array|string|null
    {
        $name = preg_replace('/(bundle|controller)_/', '_', parent::getDefaultRouteName($class, $method));

        if (str_ends_with($method->name, 'Action') || str_ends_with($method->name, '_action')) {
            $name = preg_replace('/action(_\d+)?$/', '\\1', $name);
        }

        return str_replace('__', '_', $name);
    }
}
