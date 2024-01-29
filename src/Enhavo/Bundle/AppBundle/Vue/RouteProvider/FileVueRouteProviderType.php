<?php

namespace Enhavo\Bundle\AppBundle\Vue\RouteProvider;

use Enhavo\Component\Type\AbstractType;
use Symfony\Component\Config\Resource\FileResource;
use Symfony\Component\Filesystem\Path;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Yaml\Yaml;

class FileVueRouteProviderType extends AbstractType implements VueRouteProviderTypeInterface
{
    use VueProviderTypeHelperTrait;

    /** @var VueRoute[] */
    private ?array $routes = null;

    public function __construct(
        private string $basePath,
    )
    {
    }

    public function getRoutes($options, array|string|null $groups = null): array
    {
        if (!$this->isGroupSelected($options, $groups)) {
            return [];
        }

        $this->load($options);

        return $this->routes;
    }

    public function getRoute($options, $path, array|string|null $groups = null): ?VueRoute
    {
        if (!$this->isGroupSelected($options, $groups)) {
            return null;
        }

        $this->load($options);
        return $this->search($path, $this->routes);
    }



    private function load($options): void
    {
        if ($this->routes !== null) {
            return;
        }

        $resource = new FileResource(Path::join($this->basePath, $options['path']));
        $content = $this->getContent($resource);
        if (is_string($options['prefix'])) {
            $content = $this->prefixRoutes($content, $options['prefix']);
        }

        $this->routes = [];
        foreach ($content as $routeContent) {
            $this->routes[] = new VueRoute($routeContent);
        }
    }

    private function getContent(FileResource $resource)
    {
        $ext = pathinfo($resource->getResource(), PATHINFO_EXTENSION);

        if ($ext === 'yaml') {
            return Yaml::parseFile($resource->getResource());
        } elseif ($ext === 'json') {
            return json_decode(file_get_contents($resource->getResource()), true);
        }

        throw new \Exception(sprintf('VueRouterContentLoader can only read yaml or json formats. Trying to read "%s"', $resource->getResource()));
    }

    private function prefixRoutes($routes, $prefix)
    {
        foreach ($routes as $key => $route) {
            if (isset($route['path'])) {
                $routes[$key]['path'] = Path::join($prefix, $route['path']);
            }

            if (isset($route['children'])) {
                $routes[$key]['children'] = $this->prefixRoutes($route['children'], $prefix);
            }
        }

        return $routes;
    }

    public static function getName(): ?string
    {
        return 'file';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'prefix' => null,
            'groups' => null,
        ]);

        $resolver->setRequired(['path']);
    }
}
