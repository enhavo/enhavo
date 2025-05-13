<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
     *
     * @return StrategyInterface
     */
    public function getStrategy($strategy)
    {
        $serviceName = sprintf('enhavo_app.preview.strategy.%s', $strategy);

        return $this->container->get($serviceName);
    }
}
