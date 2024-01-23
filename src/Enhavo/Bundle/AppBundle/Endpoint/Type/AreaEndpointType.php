<?php

namespace Enhavo\Bundle\AppBundle\Endpoint\Type;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ApiBundle\Endpoint\AbstractEndpointType;
use Enhavo\Bundle\ApiBundle\Endpoint\Context;
use Enhavo\Bundle\AppBundle\Routing\RouteCollectorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Twig\Environment;

class AreaEndpointType extends AbstractEndpointType
{
    public function __construct(
        private readonly Environment $twig,
        private readonly RouteCollectorInterface $routeCollector,
    ) {}

    public function handleRequest($options, Request $request, Data $data, Context $context)
    {
        if ($options['routes']) {
            $routesCollection = $this->routeCollector->getRouteCollection($options['routes']);
            $context->set('routes', $routesCollection);
            $data->set('routes', $this->normalize($routesCollection, null, ['groups' => 'endpoint']));
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setRequired('area');

        $resolver->setDefaults([
            'template' => null,
            'routes' => false,
        ]);

        $resolver->setNormalizer('template', function($options, $value) {
            return $this->twig->createTemplate($value)->render(['area' => $options['area']]);
        });
    }

    public static function getParentType(): ?string
    {
        return ViewEndpointType::class;
    }

    public static function getName(): ?string
    {
        return 'area';
    }
}
