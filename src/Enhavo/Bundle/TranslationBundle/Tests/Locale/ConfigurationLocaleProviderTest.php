<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\TranslationBundle\Tests\Locale;

use Enhavo\Bundle\TranslationBundle\Locale\ConfigurationLocaleProvider;
use PHPUnit\Framework\TestCase;

class ConfigurationLocaleProviderTest extends TestCase
{
    public function testGetters()
    {
        $localeProvider = new ConfigurationLocaleProvider(['de', 'en'], 'de');

        $this->assertEquals('de', $localeProvider->getDefaultLocale());
        $this->assertCount(2, $localeProvider->getLocales());
        $this->assertEquals('de', $localeProvider->getLocales()[0]);
        $this->assertEquals('en', $localeProvider->getLocales()[1]);
    }
}
