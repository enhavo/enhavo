<?php

namespace Enhavo\Bundle\ResourceBundle\RouteResolver;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\RouterInterface;

class RouteNameRouteResolver implements RouteResolverInterface
{
    public function __construct(
        private RequestStack $requestStack,
        private RouterInterface $router,
    )
    {
    }

    public function getRoute(string $name, array $context = []): ?string
    {
        $request = $this->requestStack->getMainRequest();
        if ($request) {
            $routeName = $request->attributes->get('_route');
            if ($routeName) {
                $parts = explode('_', $routeName);

                if (isset($context['api']) && $context['api'] === false) {
                    foreach ($parts as $key => $part) {
                        if ($part === 'api') {
                            unset($parts[$key]);
                            break;
                        }
                    }
                }

                array_pop($parts);
                $parts[] = $name;
                $newRouteName = implode('_', $parts);
                if ($this->router->getRouteCollection()->get($newRouteName) !== null) {
                    return $newRouteName;
                }
            }
        }

        return null;
    }
}
