<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2020-06-04
 * Time: 11:59
 */

namespace Enhavo\Bundle\AppBundle\Tests\Mock;

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
        $url = $name;
        if($parameters) {
            $query = http_build_query($parameters);
            $url = sprintf('/%s?%s', $url, $query);
        }
        return $url;
    }

    public function match($pathinfo): array
    {
        return [];
    }
}
