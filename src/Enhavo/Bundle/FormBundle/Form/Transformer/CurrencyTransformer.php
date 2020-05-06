<?php


namespace Enhavo\Bundle\FormBundle\Form\Transformer;


use Enhavo\Bundle\FormBundle\Formatter\CurrencyFormatter;
use Symfony\Component\Form\DataTransformerInterface;

class CurrencyTransformer implements DataTransformerInterface
{
    /** @var CurrencyFormatter */
    private $currencyFormatter;

    /**
     * CurrencyTransformer constructor.
     * @param CurrencyFormatter $currencyFormatter
     */
    public function __construct(CurrencyFormatter $currencyFormatter)
    {
        $this->currencyFormatter = $currencyFormatter;
    }

    /**
     * @inheritDoc
     */
    public function transform($currencyAsInt)
    {
        //int -> text
        return $this->currencyFormatter->getCurrency($currencyAsInt, null);
    }

    /**
     * @inheritDoc
     */
    public function reverseTransform($currencyAsString)
    {
        //text -> int
        return $this->currencyFormatter->getInt($currencyAsString);
    }
}
