<?php

namespace Enhavo\Bundle\AppBundle\Area;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Http\FirewallMapInterface;

class FirewallAreaResolver implements AreaResolverInterface
{
    public function __construct(
        private readonly FirewallMapInterface $firewallMap,
        private readonly RequestStack $requestStack,
        private readonly array $config
    )
    {
    }

    public function resolve(): ?string
    {
        $request = $this->requestStack->getMainRequest();
        $firewallConfig = $this->firewallMap->getFirewallConfig($request);

        if (null === $firewallConfig) {
            return null;
        }

        foreach ($this->config as $key => $config) {
            if (!isset($config['firewall'])) {
                continue;
            }

            $firewalls = is_array($config['firewall']) ? $config['firewall'] : [$config['firewall']];
            if (in_array($firewallConfig->getName(), $firewalls)) {
                return $key;
            }
        }

        return null;
    }
}
