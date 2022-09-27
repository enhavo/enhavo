<?php

namespace Enhavo\Bundle\UserBundle\Configuration;

use Laminas\Stdlib\PriorityQueue;

class ChainConfigKeyProvider implements ConfigKeyProviderInterface
{
    private PriorityQueue $providers;

    public function __construct()
    {
        $this->providers = new PriorityQueue();
    }

    public function addConfigKeyProvider(ConfigKeyProviderInterface $provider, int $priority = 10)
    {
        $this->providers->insert($provider, $priority);
    }

    public function getConfigKey(): ?string
    {
        /** @var ConfigKeyProviderInterface $provider */
        foreach ($this->providers as $provider) {
            $key = $provider->getConfigKey();
            if ($key !== null) {
                return $key;
            }
        }
        return null;
    }
}
