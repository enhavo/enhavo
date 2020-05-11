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
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Controller\ArgumentResolverInterface;

class ServiceStrategy implements StrategyInterface
{
    use ContainerAwareTrait;

    /** @var ArgumentResolverInterface */
    private $argumentResolver;

    /** @var RequestStack */
    private $requestStack;

    /**
     * ServiceStrategy constructor.
     * @param ArgumentResolverInterface $argumentResolver
     * @param RequestStack $requestStack
     */
    public function __construct(ArgumentResolverInterface $argumentResolver, RequestStack $requestStack)
    {
        $this->argumentResolver = $argumentResolver;
        $this->requestStack = $requestStack;
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

        $controller = array($invokeService, $invokeFunction);
        $arguments = [];

        try {
            $arguments = $this->argumentResolver->getArguments($this->requestStack->getCurrentRequest(), $controller);
        } catch (\RuntimeException $exception) {

        }

        array_unshift($arguments, $resource);

        return call_user_func($controller, ...$arguments);
    }
}
