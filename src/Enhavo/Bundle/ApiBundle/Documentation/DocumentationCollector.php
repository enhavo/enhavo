<?php

namespace Enhavo\Bundle\ApiBundle\Documentation;

use Enhavo\Bundle\ApiBundle\Documentation\Model\Documentation;
use Enhavo\Bundle\ApiBundle\Endpoint\EndpointFactoryAwareInterface;
use Enhavo\Bundle\ApiBundle\Endpoint\EndpointFactoryTrait;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\RouterInterface;

class DocumentationCollector implements EndpointFactoryAwareInterface
{
    use EndpointFactoryTrait;

    const DEFAULT = 'default';

    public function __construct(
        private RouterInterface $router,
        private array $sectionConfig,
    )
    {
    }

    public function collect($section = self::DEFAULT): array
    {
        if (!$this->hasSection($section)) {
            throw new \Exception(sprintf('Section "%s" does not exists. Maybe you forgot to add it to the configuration. Available sections: "%s"',
                $section,
                join(',', $this->getSections()
            )));
        }

        $sectionConfig = $this->sectionConfig[$section];

        /** @var RouteCollection $routes */
        $routes = $this->router->getRouteCollection();

        $documentation = new Documentation();

        $documentation->version($sectionConfig['version']);
        $documentation
            ->info()
                ->title($sectionConfig['info']['title'])
                ->description($sectionConfig['info']['description'])
                ->version($sectionConfig['info']['version'])
            ->end();

        foreach ($routes as $route) {
            $defaults = $route->getDefaults();
            if (isset($defaults['_endpoint'], $defaults['_describe']) && $defaults['_describe']) {
                $sectionDescribe = is_bool($defaults['_describe']) ? self::DEFAULT : $defaults['_describe'];
                if ($section === $sectionDescribe) {
                    $endpoint = $this->createEndpoint($defaults['_endpoint']);
                    $path = $documentation->path($route);
                    $endpoint->describe($path);
                }
            }
        }

        return $documentation->getOutput();
    }

    public function hasSection($section)
    {
        return array_key_exists($section, $this->sectionConfig);
    }

    private function getSections(): array
    {
        return array_keys($this->sectionConfig);
    }
}
