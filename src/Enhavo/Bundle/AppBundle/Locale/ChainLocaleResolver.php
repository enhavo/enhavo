<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\AppBundle\Locale;

class ChainLocaleResolver implements LocaleResolverInterface
{
    /** @var ChainResolverEntry[] */
    private $entries = [];

    public function addResolver(LocaleResolverInterface $resolver, $priority = 100)
    {
        $this->entries[] = new ChainResolverEntry($resolver, $priority);
        usort($this->entries, function (ChainResolverEntry $entryOne, ChainResolverEntry $entryTwo) {
            return $entryTwo->getPriority() - $entryOne->getPriority();
        });
    }

    public function resolve()
    {
        foreach ($this->entries as $entry) {
            $locale = $entry->getResolver()->resolve();
            if (null !== $locale) {
                return $locale;
            }
        }

        return null;
    }
}
