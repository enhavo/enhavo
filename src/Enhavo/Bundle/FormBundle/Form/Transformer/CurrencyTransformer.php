<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\FormBundle\Form\Transformer;

use Enhavo\Bundle\FormBundle\Formatter\CurrencyFormatter;
use Symfony\Component\Form\DataTransformerInterface;

class CurrencyTransformer implements DataTransformerInterface
{
    /** @var CurrencyFormatter */
    private $currencyFormatter;

    /**
     * CurrencyTransformer constructor.
     */
    public function __construct(CurrencyFormatter $currencyFormatter)
    {
        $this->currencyFormatter = $currencyFormatter;
    }

    public function transform($currencyAsInt)
    {
        // int -> text
        return $this->currencyFormatter->getCurrency($currencyAsInt, null);
    }

    public function reverseTransform($currencyAsString)
    {
        // text -> int
        return $this->currencyFormatter->getInt($currencyAsString);
    }
}
