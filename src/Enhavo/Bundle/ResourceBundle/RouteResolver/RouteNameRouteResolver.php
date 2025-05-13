<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\ResourceBundle\RouteResolver;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\RouterInterface;

class RouteNameRouteResolver implements RouteResolverInterface
{
    public function __construct(
        private RequestStack $requestStack,
        private RouterInterface $router,
    ) {
    }

    public function getRoute(string $name, array $context = []): ?string
    {
        $request = $this->requestStack->getMainRequest();
        if ($request) {
            $routeName = $request->attributes->get('_route');
            if ($routeName) {
                $parts = explode('_', $routeName);

                if (isset($context['api']) && false === $context['api']) {
                    foreach ($parts as $key => $part) {
                        if ('api' === $part) {
                            unset($parts[$key]);
                            break;
                        }
                    }
                }

                array_pop($parts);
                $parts[] = $name;
                $newRouteName = implode('_', $parts);
                if (null !== $this->router->getRouteCollection()->get($newRouteName)) {
                    return $newRouteName;
                }
            }
        }

        return null;
    }
}
