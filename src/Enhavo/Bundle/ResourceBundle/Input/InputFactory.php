<?php

namespace Enhavo\Bundle\ResourceBundle\Input;

use Enhavo\Bundle\ResourceBundle\Exception\InputException;
use Psr\Container\ContainerInterface;
use Symfony\Component\OptionsResolver\Exception\InvalidArgumentException;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InputFactory
{
    private ContainerInterface $container;

    public function __construct(
        private array $configurations,
        private string $defaultClass = Input::class,
    )
    {
    }

    public function setContainer(ContainerInterface $container): void
    {
        $this->container = $container;
    }

    public function create($key): InputInterface
    {
        if (!isset($this->configurations[$key])) {
            throw InputException::configurationNotExits($key);
        }

        $configuration = $this->configurations[$key];

        $class = $this->defaultClass;
        if (isset($configuration['class'])) {
            $class = $configuration['class'];
            unset($configuration['class']);
        }

        if ($this->container->has($class)) {
            $input = clone $this->container->get($class);
        } else {
            $input = new $class();
        }

        if (!$input instanceof InputInterface) {
            throw InputException::notImplementInputInterface($input);
        }

        try {
            $optionResolver = new OptionsResolver();
            $input->configureOptions($optionResolver);
            $options = $optionResolver->resolve($configuration);
            $input->setOptions($options);
        } catch (InvalidArgumentException $exception) {
            throw new \Exception(sprintf('Input "%s": %s', $key, $exception->getMessage()), 0, $exception);
        }

        return $input;
    }
}
