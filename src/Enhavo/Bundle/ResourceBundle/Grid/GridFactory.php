<?php

namespace Enhavo\Bundle\ResourceBundle\Grid;

use Enhavo\Bundle\ResourceBundle\Exception\GridException;
use Psr\Container\ContainerInterface;
use Symfony\Component\OptionsResolver\Exception\InvalidArgumentException;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GridFactory
{
    private ContainerInterface $container;

    public function __construct(
        private readonly array $configurations,
    )
    {
    }

    public function setContainer(ContainerInterface $container): void
    {
        $this->container = $container;
    }

    public function create($key): GridInterface
    {
        if (!isset($this->configurations[$key])) {
            throw GridException::configurationNotExits($key);
        }

        $configuration = $this->configurations[$key];
        
        if (!isset($configuration['class'])) {
            throw GridException::configurationClassMissing($key);
        }

        $class = $configuration['class'];
        unset($configuration['class']);

        if (isset($configuration['priority'])) {
            unset($configuration['priority']);
        }

        if (isset($configuration['extends'])) {
            unset($configuration['extends']);
        }

        if (isset($configuration['overwrite'])) {
            unset($configuration['overwrite']);
        }

        if ($this->container->has($class)) {
            $grid = clone $this->container->get($class);
        } else {
            $grid = new $class();
        }

        if (!$grid instanceof GridInterface) {
            throw GridException::notImplementGridInterface($grid);
        }

        try {
            $optionResolver = new OptionsResolver();
            $grid->configureOptions($optionResolver);
            $options = $optionResolver->resolve($configuration);
            $grid->setOptions($options);
        } catch (InvalidArgumentException $exception) {
            throw new \Exception(sprintf('Grid "%s": %s', $key, $exception->getMessage()), 0, $exception);
        }

        return $grid;
    }
}
