<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\TranslationBundle\Tests\Entity;

use Enhavo\Bundle\TranslationBundle\Entity\TranslationRoute;
use PHPUnit\Framework\TestCase;

class TranslationRouteTest extends TestCase
{
    public function testGettersSetters()
    {
        $route = new TranslationRoute();
        $route->setLocale('_de');
        $route->setProperty('_prop');
        $this->assertEquals('_de', $route->getLocale());
        $this->assertEquals('_prop', $route->getProperty());
    }
}
