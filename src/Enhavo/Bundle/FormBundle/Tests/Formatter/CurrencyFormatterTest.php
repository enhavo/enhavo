<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\FormBundle\Tests\Formatter;

use Enhavo\Bundle\FormBundle\Formatter\CurrencyFormatter;
use PHPUnit\Framework\TestCase;

class CurrencyFormatterTest extends TestCase
{
    public function testGetCurrency()
    {
        $formatter = new CurrencyFormatter();

        $amount = 1123;
        $formatted = $formatter->getCurrency($amount, 'UERO', 'right');
        $this->assertEquals('11,23 UERO', $formatted);
        $formatted = $formatter->getCurrency($amount, 'UERO', 'left');
        $this->assertEquals('UERO 11,23', $formatted);

        $amount = -3210;
        $formatted = $formatter->getCurrency($amount, 'UERO', 'right');
        $this->assertEquals('-32,10 UERO', $formatted);
        $formatted = $formatter->getCurrency($amount, 'UERO', 'left');
        $this->assertEquals('UERO -32,10', $formatted);

        $amount = -21;
        $formatted = $formatter->getCurrency($amount, 'UERO', 'right');
        $this->assertEquals('-0,21 UERO', $formatted);
        $formatted = $formatter->getCurrency($amount, 'UERO', 'left');
        $this->assertEquals('UERO -0,21', $formatted);

        $formatted = $formatter->getCurrency($amount);
        $this->assertEquals('-0,21 Euro', $formatted);
    }

    public function testGetInt()
    {
        $formatter = new CurrencyFormatter();

        $result = $formatter->getInt('798');
        $this->assertEquals(79800, $result);

        $result = $formatter->getInt('-381,28');
        $this->assertEquals(-38128, $result);

        $result = $formatter->getInt('-0,33');
        $this->assertEquals(-33, $result);

        $result = $formatter->getInt('0,46');
        $this->assertEquals(46, $result);
    }
}
