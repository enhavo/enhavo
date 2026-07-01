<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\ApiBundle\Documentation;

use Enhavo\Bundle\ApiBundle\Documentation\Model\Documentation;
use Symfony\Component\DependencyInjection\ServiceLocator;

class DocumentationGenerator
{
    public const SECTION_DEFAULT = 'default';

    private ?ServiceLocator $container = null;

    public function __construct(
        private array $sectionConfig,
    ) {
    }

    public function setContainer(ServiceLocator $container)
    {
        $this->container = $container;
    }

    public function generate($section = self::SECTION_DEFAULT): array
    {
        if (!$this->hasSection($section)) {
            throw new \Exception(sprintf('Section "%s" does not exist. Maybe you forgot to add it to the configuration. Available sections: "%s"', $section, join(',', $this->getSections())));
        }

        $documentation = new Documentation();

        $documentation->version("3.0.0");

        foreach ($this->sectionConfig[$section] as $service => $options) {
            $this->container->get($service)->describe($documentation, $options ?? []);
        }

        return $documentation->getOutput();
    }

    public function hasSection($section): bool
    {
        return array_key_exists($section, $this->sectionConfig);
    }
}
