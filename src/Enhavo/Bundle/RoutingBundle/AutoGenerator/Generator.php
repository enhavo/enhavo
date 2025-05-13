<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
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
