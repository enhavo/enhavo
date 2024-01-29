<?php

namespace Enhavo\Bundle\RoutingBundle\Vue;

use Doctrine\ORM\AbstractQuery;
use Enhavo\Bundle\AppBundle\Vue\RouteProvider\VueProviderTypeHelperTrait;
use Enhavo\Bundle\AppBundle\Vue\RouteProvider\VueRoute;
use Enhavo\Bundle\AppBundle\Vue\RouteProvider\VueRouteProviderTypeInterface;
use Enhavo\Bundle\RoutingBundle\Repository\RouteRepository;
use Enhavo\Component\Type\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RoutingVueRouteProviderType extends AbstractType implements VueRouteProviderTypeInterface
{
    use VueProviderTypeHelperTrait;

    public function __construct(
        private readonly RouteRepository $routeRepository,
    )
    {
    }

    public function getRoutes($options, array|string|null $groups = null): array
    {
        if (!$this->isGroupSelected($options, $groups)) {
            return [];
        }

        $routes = $this->routeRepository->createQueryBuilder('r')
            ->where('r.contentClass = :contentClass')
            ->setParameter('contentClass', $options['resource_key'])
            ->getQuery()->getResult(AbstractQuery::HYDRATE_ARRAY);

        $vueRoutes = [];
        foreach ($routes as $route) {
            $vueRoutes[] = $this->createVueRoute($route, $options);
        }
        return $vueRoutes;
    }

    public function getRoute($options, $path, array|string|null $groups = null): ?VueRoute
    {
        if (!$this->isGroupSelected($options, $groups)) {
            return null;
        }

        $routes = $this->routeRepository->createQueryBuilder('r')
            ->where('r.contentClass = :contentClass')
            ->andWhere('r.staticPrefix = :staticPrefix')
            ->setParameter('contentClass', $options['resource_key'])
            ->setParameter('staticPrefix', $path)
            ->setMaxResults(1)
            ->getQuery()->getResult(AbstractQuery::HYDRATE_ARRAY);

        if (count($routes) > 0) {
            return $this->createVueRoute($routes[0], $options);
        }

        return null;
    }

    private function createVueRoute(array $route, $options): VueRoute
    {
        return new VueRoute([
            'path' => $route['staticPrefix'],
            'component' => $options['component'],
            'meta' => [
                'name' => $options['meta_name'],
                'id' => $route['contentId'],
            ]
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'groups' => null,
        ]);

        $resolver->setRequired(['component', 'resource_key', 'meta_name']);
    }
}
