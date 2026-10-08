<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\UserBundle\User;

use Enhavo\Bundle\UserBundle\Configuration\ConfigurationProvider;
use Symfony\Bundle\SecurityBundle\Security\FirewallMap;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class TargetPathResolver
{
    use TargetPathTrait;

    public function __construct(
        private readonly ConfigurationProvider $configurationProvider,
        private readonly FirewallMap $firewallMap,
        private readonly UrlGeneratorInterface $urlGenerator,
    ) {
    }

    public function resolveTargetPath(Request $request): string
    {
        $firewallName = $this->firewallMap->getFirewallConfig($request)->getName();

        $targetPath = $request->query->get('redirect') ?? $this->getTargetPath($request->getSession(), $firewallName);
        $this->removeTargetPath($request->getSession(), $firewallName);
        $request->getSession()->set('_security.credentials', null);

        if (null === $targetPath) {
            $configuration = $this->configurationProvider->getLoginConfiguration();
            $targetPath = $this->urlGenerator->generate($configuration->getRedirectRoute());
        }

        return $targetPath;
    }
}
