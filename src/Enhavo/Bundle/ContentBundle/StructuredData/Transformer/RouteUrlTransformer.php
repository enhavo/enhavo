<?php

namespace Enhavo\Bundle\ContentBundle\StructuredData\Transformer;

use Enhavo\Bundle\ContentBundle\StructuredData\StructuredDataTransformerInterface;
use Enhavo\Bundle\RoutingBundle\Model\RouteInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;

class RouteUrlTransformer implements StructuredDataTransformerInterface
{
    public function __construct(
        private readonly RouterInterface $router,
    ) {}

    public function transform(mixed $value, array $options)
    {
        if ($value === null) {
            return null;
        }

        if (!($value instanceof RouteInterface)) {
            throw new \Exception(sprintf('Structured Data Transformation Error: Transformer type %s only works on property type %s', self::class, RouteInterface::class));
        }

        return $this->router->generate($value->getName(), [], UrlGeneratorInterface::ABSOLUTE_URL);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
    }

    public static function getName(): ?string
    {
        return 'route_url';
    }
}
