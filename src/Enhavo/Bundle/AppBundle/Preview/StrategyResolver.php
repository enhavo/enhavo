<?php

/**
 * StrategyResolver.php
 *
 * @since 18/11/15
 * @author gseidel
 */


namespace Enhavo\Bundle\AppBundle\Preview;

use Symfony\Component\DependencyInjection\ContainerInterface;

class StrategyResolver
{
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param string $strategy
     * @return StrategyInterface
     */
    public function getStrategy($strategy)
    {
        $serviceName = sprintf('enhavo_app.preview.strategy.%s', $strategy);
        return $this->container->get($serviceName);
    }
}