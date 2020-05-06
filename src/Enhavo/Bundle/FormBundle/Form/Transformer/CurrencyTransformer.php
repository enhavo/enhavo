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
     */
    public function __construct()
    {
        $this->currencyFormatter = new CurrencyFormatter();
    }


    /**
     * @inheritDoc
     */
    public function transform($currencyAsInt)
    {
        //int -> text
        $this->currencyFormatter->getCurrency($currencyAsInt, null);
    }

    /**
     * @inheritDoc
     */
    public function reverseTransform($currencyAsString)
    {
        //text -> int
        $this->currencyFormatter->getInt($currencyAsString);
    }
}
