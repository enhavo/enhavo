<?php

/**
 * SlugStrategy.php
 *
 * @since 18/11/15
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\Preview\Strategy;

use Enhavo\Bundle\AppBundle\Exception\PreviewException;
use Enhavo\Bundle\AppBundle\Preview\StrategyInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException;

class ServiceStrategy implements StrategyInterface
{
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function getPreviewResponse($resource, $options = array())
    {
        $service = $options['service'];
        if($service === null) {
            throw new PreviewException(
                'You choose the strategy service, but you didn\'t pass any service to call. Please use the service parameter with "service_id:functionName" notation'
            );
        }
        $parts = preg_split('#:#', $service);
        if(count($parts) !== 2) {
            throw new PreviewException(
                sprintf('The service parameter need a notation like "service_id:functionName" but you got "%s"', $service)
            );
        }

        $serviceName = $parts[0];
        $invokeService = null;
        try {
            $invokeService = $this->container->get($serviceName);
        } catch(ServiceNotFoundException $e) {
            throw new PreviewException(
                sprintf('The service parameter in preview route should be an existing service, got "%s"', $serviceName)
            );
        }

        $invokeFunction = $parts[1];
        if(!method_exists($invokeService, $invokeFunction)) {
            throw new PreviewException(
                sprintf(
                    'The defined function "%s " in service "%s" for preview route does not exists, check your service config "%s"',
                    $invokeFunction,
                    $serviceName,
                    $service
                )
            );
        }

        return call_user_func(array($invokeService, $invokeFunction), $resource);
    }
}