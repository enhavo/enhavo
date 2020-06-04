<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2020-06-04
 * Time: 11:59
 */

namespace Enhavo\Bundle\AppBundle\Tests\Mock;

use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\RouterInterface;

class RouterMock implements RouterInterface
{
    public function setContext(RequestContext $context)
    {
        // TODO: Implement setContext() method.
    }

    public function getContext()
    {
        // TODO: Implement getContext() method.
    }

    public function getRouteCollection()
    {
        // TODO: Implement getRouteCollection() method.
    }

    public function generate($name, $parameters = [], $referenceType = self::ABSOLUTE_PATH)
    {
        $url = $name;
        if($parameters) {
            $query = http_build_query($parameters);
            $url = sprintf('/%s?%s', $url, $query);
        }
        return $url;
    }

    public function match($pathinfo)
    {
        // TODO: Implement match() method.
    }
}
