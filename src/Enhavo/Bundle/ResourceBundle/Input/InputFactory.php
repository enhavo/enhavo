<?php

namespace Enhavo\Bundle\ResourceBundle\Input;

use Enhavo\Bundle\ResourceBundle\Exception\GridException;
use Enhavo\Bundle\ResourceBundle\Grid\GridInterface;
use Enhavo\Bundle\ResourceBundle\Grid\GridOptionsInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InputFactory
{
    public function __construct(
        private array $configurations,
        private string $defaultClass,
    )
    {
    }

    public function create($key): GridInterface
    {
        if (!isset($this->configurations[$key])) {
            throw GridException::configurationNotExits($key);
        }

        $class = $this->defaultClass;
        if (isset($this->configurations[$key]['class'])) {
            $class = $this->configurations[$key]['class'];
        }

        $grid = new $class();

        $configuration = $this->configurations[$key];
        if ($grid instanceof GridOptionsInterface) {
            $optionResolver = new OptionsResolver();
            $grid->configureOptions($optionResolver);
            $configuration = $optionResolver->resolve($this->configurations[$key]);
        }

        if (!$grid instanceof GridInterface) {
            throw GridException::notImplementGridInterface($grid);
        }

        $grid->setConfiguration($configuration);

        return $grid;
    }
}
