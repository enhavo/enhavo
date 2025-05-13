<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\AppBundle\Preview\Strategy;

use Enhavo\Bundle\AppBundle\Exception\PreviewException;
use Enhavo\Bundle\AppBundle\Preview\StrategyInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author gseidel
 */
class DynamicStrategy implements StrategyInterface
{
    use ContainerAwareTrait;

    public function getPreviewResponse($resource, $options = [])
    {
        $map = $this->container->getParameter('cmf_routing.controllers_by_class');
        $controllerDefinition = null;
        foreach ($map as $class => $value) {
            if ($resource instanceof $class) {
                $controllerDefinition = $value;
                break;
            }
        }

        if (null === $controllerDefinition) {
            throw new PreviewException(sprintf('No controller found for resource, did you add "%s" to cmf_routing.dynamic.controller_by_class in your configuration?', get_class($resource)));
        }

        try {
            $request = new Request([], [], ['_controller' => $controllerDefinition]);
            $controller = $this->container->get('debug.controller_resolver')->getController($request);
            $response = call_user_func_array($controller, [$resource]);
        } catch (\Exception $e) {
            throw new PreviewException(sprintf('Something went wrong while trying to invoke the controller "%s", this "%s" was thrown before with message: %s', $controllerDefinition, get_class($e), $e->getMessage()));
        }

        return $response;
    }
}
