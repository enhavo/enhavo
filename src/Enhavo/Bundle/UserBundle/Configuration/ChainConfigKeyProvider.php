<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\UserBundle\Configuration;

use Enhavo\Bundle\FrameworkBundle\Util\PriorityCollection;

class ChainConfigKeyProvider implements ConfigKeyProviderInterface
{
    private PriorityCollection $providers;

    public function __construct()
    {
        $this->providers = new PriorityCollection();
    }

    public function addConfigKeyProvider(ConfigKeyProviderInterface $provider, int $priority = 10)
    {
        $this->providers->add($provider, $priority);
    }

    public function getConfigKey(): ?string
    {
        /** @var ConfigKeyProviderInterface $provider */
        foreach ($this->providers as $provider) {
            $key = $provider->getConfigKey();
            if (null !== $key) {
                return $key;
            }
        }

        return null;
    }
}
