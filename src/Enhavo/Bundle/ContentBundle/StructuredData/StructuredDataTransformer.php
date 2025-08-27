<?php

namespace Enhavo\Bundle\ContentBundle\StructuredData;

use Psr\Container\ContainerInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StructuredDataTransformer
{
    private ?ContainerInterface $container = null;

    public function setContainer(ContainerInterface $container): void
    {
        $this->container = $container;
    }

    public function transform(string $type, mixed $value, array $options = []): mixed
    {
        /** @var StructuredDataTransformerInterface $transformer */
        $transformer = $this->container->get($type);

        $resolver = new OptionsResolver();
        $transformer->configureOptions($resolver);
        return $transformer->transform($value, $resolver->resolve($options));
    }
}
