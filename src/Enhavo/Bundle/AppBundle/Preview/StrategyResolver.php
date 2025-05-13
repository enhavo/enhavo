<?php

namespace Enhavo\Bundle\AppBundle\Preview;

use Symfony\Component\DependencyInjection\ContainerAwareTrait;

/**
 * @author gseidel
 */
class StrategyResolver
{
    use ContainerAwareTrait;

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
