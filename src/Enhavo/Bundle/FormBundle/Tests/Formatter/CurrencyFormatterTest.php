<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2020-03-16
 * Time: 11:08
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
}
