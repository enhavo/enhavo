<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\FormBundle\Form\Type;

use Enhavo\Bundle\FormBundle\Form\Transformer\CurrencyTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @author gseidel
 */
class CurrencyType extends AbstractType
{
    /** @var CurrencyTransformer */
    private $currencyTransformer;

    /**
     * CurrencyType constructor.
     */
    public function __construct(CurrencyTransformer $currencyTransformer)
    {
        $this->currencyTransformer = $currencyTransformer;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addModelTransformer($this->currencyTransformer);
    }

    public function getParent()
    {
        return TextType::class;
    }

    public function getBlockPrefix()
    {
        return 'enhavo_currency';
    }
}
