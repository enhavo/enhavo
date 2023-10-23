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
        private Environment $twig,
        private RouteCollectorInterface $routeCollector,
    ) {}

    public function handleRequest($options, Request $request, Data $data, Context $context)
    {
        $routesCollection = $this->routeCollector->getRouteCollection($options['area']);
        $data->set('routes', $this->normalize($routesCollection, null, ['groups' => 'endpoint']));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setRequired('area');

        $resolver->setNormalizer('template', function($options, $value) {
            return $this->twig->createTemplate($value)->render(['area' => $options['area']]);
        });
    }

    public static function getParentType(): ?string
    {
        return ViewEndpointType::class;
    }
}
