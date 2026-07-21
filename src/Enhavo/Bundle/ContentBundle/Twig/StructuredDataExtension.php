<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\ContentBundle\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class StructuredDataExtension extends AbstractExtension
{
    #[\Override]
    public function getFunctions(): array
    {
        return [
            new TwigFunction('structured_data', $this->renderStructuredData(...), ['is_safe' => ['html']]),
        ];
    }

    public function renderStructuredData(?array $structuredData): string
    {
        if (!$structuredData) {
            return '';
        }

        $graph = [];
        foreach ($structuredData as $structuredDataItem) {
            if ($this->notEmpty($structuredDataItem)) {
                $graph[] = $structuredDataItem;
            }
        }

        return '<script type="application/ld+json">'.json_encode([
            '@context' => 'https://schema.org',
            '@graph' => $graph,
        ]).'</script>';
    }

    private function notEmpty(array $structuredDataItem): bool
    {
        $keys = array_keys($structuredDataItem);
        foreach ($keys as $key) {
            if ('@context' !== $key && '@type' !== $key) {
                return true;
            }
        }

        return false;
    }
}
