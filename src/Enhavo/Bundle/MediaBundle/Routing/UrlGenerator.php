<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\MediaBundle\Routing;

use Enhavo\Bundle\MediaBundle\Model\FileInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Generator\UrlGenerator as SymfonyUrlGenerator;
use Symfony\Component\Security\Http\FirewallMapInterface;

class UrlGenerator implements UrlGeneratorInterface
{
    public function __construct(
        private readonly FirewallMapInterface $firewallMap,
        private readonly RequestStack $requestStack,
        private readonly AdminUrlGenerator $adminUrlGenerator,
        private readonly ThemeUrlGenerator $themeUrlGenerator,
    ) {
    }

    public function generate(FileInterface $file, $referenceType = SymfonyUrlGenerator::ABSOLUTE_PATH): string
    {
        if ('admin' === $this->getFirewallName()) {
            return $this->adminUrlGenerator->generate($file, $referenceType);
        }

        return $this->themeUrlGenerator->generate($file, $referenceType);
    }

    public function generateFormat(FileInterface $file, string $format, $referenceType = SymfonyUrlGenerator::ABSOLUTE_PATH): string
    {
        if ('admin' === $this->getFirewallName()) {
            return $this->adminUrlGenerator->generateFormat($file, $format, $referenceType);
        }

        return $this->themeUrlGenerator->generateFormat($file, $format, $referenceType);
    }

    private function getFirewallName(): ?string
    {
        $request = $this->requestStack->getMainRequest();
        if (null === $request) {
            return null;
        }
        $firewallConfig = $this->firewallMap->getFirewallConfig($request);

        return $firewallConfig?->getName();
    }
}
