<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 03.09.17
 * Time: 23:26
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

    )
    {
    }

    public function generate(FileInterface $file, $referenceType = SymfonyUrlGenerator::ABSOLUTE_PATH): string
    {
        if ($this->getFirewallName() === 'admin') {
            return $this->adminUrlGenerator->generateFormat($file, $referenceType);
        }
        return $this->themeUrlGenerator->generateFormat($file, $referenceType);
    }

    public function generateFormat(FileInterface $file, string $format, $referenceType = SymfonyUrlGenerator::ABSOLUTE_PATH): string
    {
        if ($this->getFirewallName() === 'admin') {
            return $this->adminUrlGenerator->generateFormat($file, $referenceType);
        }
        return $this->themeUrlGenerator->generateFormat($file, $referenceType);
    }

    private function getFirewallName(): ?string
    {
        $request = $this->requestStack->getMainRequest();
        $firewallConfig = $this->firewallMap->getFirewallConfig($request);
        return $firewallConfig?->getName();
    }
}
