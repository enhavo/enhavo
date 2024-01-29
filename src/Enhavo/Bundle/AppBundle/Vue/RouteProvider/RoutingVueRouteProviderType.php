<?php

namespace Enhavo\Bundle\AppBundle\Vue\RouteProvider;

use Enhavo\Component\Type\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\RouterInterface;

class RoutingVueRouteProviderType extends AbstractType implements VueRouteProviderTypeInterface
{
    use VueProviderTypeHelperTrait;

    private ?array $routes = null;

    public function __construct(
        private RouterInterface $router,
    )
    {
    }

    public function getRoutes($options, array|string|null $groups = null): array
    {
        $this->load($options);
        return $this->routes;
    }

    public function getRoute($options, $path, array|string|null $groups = null): ?VueRoute
    {
        $this->load($options);
        return $this->search($path, $this->routes);
    }

    private function load($options): void
    {
        if ($this->routes !== null) {
            return;
        }

        $routes = $this->router->getRouteCollection();

        $this->routes = [];
        foreach ($routes as $key => $route) {
            $defaults = $route->getDefaults();
            if (isset($defaults['_vue'])) {
                $vueGroups = $defaults['_vue']['groups'] ?? null;
                if ($this->isGroupSelected($options, $vueGroups)) {
                    $vueRoute = new VueRoute($defaults['_vue']);
                    $vueRoute->setName($key);
                    $vueRoute->setPath($route->getPath());
                    $this->routes[] = $vueRoute;
                }
            }
        }
    }

    public static function getName(): ?string
    {
        return 'routing';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'groups' => null,
        ]);
    }
}
