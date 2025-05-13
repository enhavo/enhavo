<?php

namespace Enhavo\Bundle\AppBundle\Preview\Strategy;

use Enhavo\Bundle\AppBundle\Exception\PreviewException;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Enhavo\Bundle\AppBundle\Preview\StrategyInterface;

/**
 * @author gseidel
 */
class DynamicStrategy implements StrategyInterface
{
    use ContainerAwareTrait;

    /**
     * {@inheritdoc}
     */
    public function getPreviewResponse($resource, $options = array())
    {
        $map = $this->container->getParameter('cmf_routing.controllers_by_class');
        $controllerDefinition = null;
        foreach ($map as $class => $value) {
            if ($resource instanceof $class) {
                $controllerDefinition = $value;
                break;
            }
        }

        if($controllerDefinition === null) {
            throw new PreviewException(
                sprintf(
                    'No controller found for resource, did you add "%s" to cmf_routing.dynamic.controller_by_class in your configuration?',
                    get_class($resource)
                )
            );
        }

        try {
            $request = new Request(array(), array(), array('_controller' => $controllerDefinition));
            $controller = $this->container->get('debug.controller_resolver')->getController($request);
            $response = call_user_func_array($controller, array($resource));
        } catch(\Exception $e) {
            throw new PreviewException(
                sprintf(
                    'Something went wrong while trying to invoke the controller "%s", this "%s" was thrown before with message: %s',
                    $controllerDefinition,
                    get_class($e),
                    $e->getMessage()
                )
            );
        }

        return $response;
    }
}
