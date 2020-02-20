<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2020-02-20
 * Time: 08:57
 */

namespace Enhavo\Bundle\RoutingBundle\AutoGenerator;

use Symfony\Component\OptionsResolver\OptionsResolver;

class Generator
{
    /**
     * @var GeneratorInterface
     */
    private $type;

    /**
     * @var array
     */
    private $options;

    /**
     * @var object
     */
    private $resource;

    public function __construct(GeneratorInterface $type, $options, $resource)
    {
        $this->type = $type;
        $this->resource = $resource;

        $this->options = $options;

        $resolver = new OptionsResolver();
        $type->configureOptions($resolver);
        $this->options = $resolver->resolve($options);
    }

    public function generate()
    {
        $this->type->generate($this->resource, $this->options);
    }
}
