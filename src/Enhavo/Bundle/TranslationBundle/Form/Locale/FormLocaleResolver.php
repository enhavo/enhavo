<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\TranslationBundle\Form\Locale;

use Enhavo\Bundle\TranslationBundle\Translation\TranslationManager;

class FormLocaleResolver implements FormLocaleResolverInterface
{
    public function __construct(
        private readonly TranslationManager $translationManager
    ) {
    }

    public function getDefaultLocale(): string
    {
        return $this->translationManager->getDefaultLocale();
    }

    public function getLocales(): array
    {
        return $this->translationManager->getLocales();
    }
}
