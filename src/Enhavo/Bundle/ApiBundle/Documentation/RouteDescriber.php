<?php

namespace Enhavo\Bundle\ApiBundle\Documentation;

use Enhavo\Bundle\ApiBundle\Documentation\Model\Documentation;
use Enhavo\Bundle\ApiBundle\Endpoint\EndpointFactoryTrait;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\RouterInterface;

class RouteDescriber implements DescriberInterface
{
    use EndpointFactoryTrait;

    public function __construct(
        private RouterInterface $router,
    ) {
    }

    public function describe(Documentation $documentation, array $options = []): void
    {
        $options = $this->getOptions($options);

        $routes = $this->router->getRouteCollection();

        foreach ($routes as $route) {
            $defaults = $route->getDefaults();
            if (isset($defaults['_endpoint'], $defaults['_describe']) && $defaults['_describe']) {
                $sectionDescribe = is_bool($defaults['_describe']) ? DocumentationGenerator::SECTION_DEFAULT : $defaults['_describe'];
                if ($options['section'] === $sectionDescribe) {
                    $endpoint = $this->createEndpoint($defaults['_endpoint']);
                    $path = $documentation->path($route);
                    $endpoint->describe($path);
                }
            }
        }
    }

    private function getOptions(array $options): array
    {
        $resolver = new OptionsResolver();
        $resolver->setDefaults([
            'section' => DocumentationGenerator::SECTION_DEFAULT
        ]);
        return $resolver->resolve($options);
    }
}
