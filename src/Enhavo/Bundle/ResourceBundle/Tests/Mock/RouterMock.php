<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\ResourceBundle\Tests\Mock;

use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\RouterInterface;

class RouterMock implements RouterInterface
{
    public function setContext(RequestContext $context)
    {
        // TODO: Implement setContext() method.
    }

    public function getContext(): RequestContext
    {
        // TODO: Implement getContext() method.
    }

    public function getRouteCollection(): RouteCollection
    {
        return new RouteCollection();
    }

    public function generate($name, $parameters = [], $referenceType = self::ABSOLUTE_PATH): string
    {
        $query = http_build_query($parameters);

        if ($query) {
            return sprintf('/%s?%s', $name, $query);
        }

        return sprintf('/%s', $name);
    }

    public function match($pathinfo): array
    {
        return [];
    }
}
