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
        $this->load($options, $groups);
        return $this->routes;
    }

    public function getRoute($options, $path, array|string|null $groups = null): ?VueRoute
    {
        if (!$this->isGroupSelected($options, $groups)) {
            return null;
        }

        $this->load($options, $groups);
        return $this->search($path, $this->routes);
    }

    private function load($options, array|string|null $groups = null): void
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
                if ($this->isGroupSelected($options, $vueGroups) && $this->inGroup($vueGroups, $groups)) {
                    $vueRoute = new VueRoute($defaults['_vue']);

                    if ($vueRoute->getName() === null) {
                        $vueRoute->setName($key);
                    }

                    if ($vueRoute->getPath() === null) {
                        $vueRoute->setPath($this->convertPath($route->getPath()));
                    }

                    $this->routes[] = $vueRoute;
                }
            }
        }
    }

    private function inGroup($vueGroups, $groups): bool
    {
        if ($vueGroups === null && $groups === null) {
            return true;
        }

        if ($groups === true) {
            return true;
        }

        if (!is_array($groups)) {
            $groups = [$groups];
        }

        if (!is_array($vueGroups)) {
            $vueGroups = [$vueGroups];
        }

        foreach ($vueGroups as $vueGroup) {
            foreach ($groups as $group) {
                if ($group === $vueGroup) {
                    return true;
                }
            }
        }

        return false;
    }

    public static function getName(): ?string
    {
        return 'routing';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'groups' => true,
        ]);
    }
}
