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

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Http\FirewallMapInterface;

class FirewallConfigKeyProvider implements ConfigKeyProviderInterface
{
    public function __construct(
        private FirewallMapInterface $firewallMap,
        private RequestStack $requestStack,
        private array $config,
    ) {
    }

    public function getConfigKey(): ?string
    {
        $request = $this->requestStack->getMainRequest();
        $firewallConfig = $this->firewallMap->getFirewallConfig($request);

        if (null === $firewallConfig) {
            return null;
        }

        foreach ($this->config as $key => $config) {
            if (in_array($firewallConfig->getName(), $config['firewalls'])) {
                return $key;
            }
        }

        return null;
    }
}
