<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 11.08.18
 * Time: 00:30
 */

namespace Enhavo\Bundle\RoutingBundle\Matcher;

use Enhavo\Bundle\AppBundle\Routing\ConditionResolverInterface;
use Symfony\Cmf\Component\Routing\NestedMatcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

class ConditionUrlMatcher extends UrlMatcher
{
    /**
     * @var ConditionResolverInterface
     */
    private $resolver;

    public function __construct(RouteCollection $routes, RequestContext $context, ConditionResolverInterface $resolver = null)
    {
        $this->resolver = $resolver;
        parent::__construct($routes, $context);
    }

    protected function handleRouteRequirements($pathinfo, $name, Route $route)
    {
        // expression condition
        if ($route->getCondition()) {
            $expression = $this->getExpressionLanguage()->evaluate($route->getCondition(), array(
                'resolver' => $this->resolver,
                'context' => $this->context,
                'request' => $this->request ?: $this->createRequest($pathinfo))
            );
            if(!$expression) {
                return array(self::REQUIREMENT_MISMATCH, null);
            }
        }

        // check HTTP scheme requirement
        $scheme = $this->context->getScheme();
        $status = $route->getSchemes() && !$route->hasScheme($scheme) ? self::REQUIREMENT_MISMATCH : self::REQUIREMENT_MATCH;

        return array($status, null);
    }
}