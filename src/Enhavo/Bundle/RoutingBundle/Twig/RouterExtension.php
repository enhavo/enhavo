<?php
/**
 * UrlResolver.php
 *
 * @since 12/10/16
 * @author gseidel
 */

namespace Enhavo\Bundle\RoutingBundle\Twig;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Enhavo\Bundle\RoutingBundle\Router\Router;

class RouterExtension extends \Twig_Extension
{
    /**
     * @var Router
     */
    private $router;

    /**
     * RouterExtension constructor.
     *
     * @param Router $router
     */
    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('router', array($this, 'generate')),
        );
    }

    /**
     * @param $resource
     * @param array $parameters
     * @param int $referenceType
     * @param string $type
     * @return string
     */
    public function generate($resource , $parameters = [], $referenceType = UrlGeneratorInterface::ABSOLUTE_PATH, $type = 'default')
    {
        return $this->router->generate($resource , $parameters, $referenceType, $type = 'default');
    }

    public function getName()
    {
        return 'enhavo_routing_router';
    }
}