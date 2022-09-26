<?php

namespace Enhavo\Bundle\UserBundle\Configuration;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Http\FirewallMapInterface;

class FirewallConfigKeyProvider implements ConfigKeyProviderInterface
{
    public function __construct(
        private FirewallMapInterface $firewallMap,
        private RequestStack $requestStack,
        private array $config
    )
    {
    }

    public function getConfigKey(): ?string
    {
        $request = $this->requestStack->getMainRequest();
        $firewallConfig = $this->firewallMap->getFirewallConfig($request);

        if (null === $firewallConfig) {
            return null;
        }

        foreach ($this->config as $key => $config) {
            if ($config['firewall'] === $firewallConfig->getName()) {
                return $key;
            }
        }

        return null;
    }
}
