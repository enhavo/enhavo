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

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class UnitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->addModelTransformer(new CallbackTransformer(
                function ($currencyAsInt) {
                    // int -> text
                    $string = (string) $currencyAsInt;
                    $string = str_replace('.', ',', $string);

                    return $string;
                },
                function ($currencyAsString) {
                    // text -> int
                    $string = $currencyAsString;
                    $string = str_replace(',', '.', $string);

                    return $string;
                }
            ));
    }

    public function getParent()
    {
        return TextType::class;
    }

    public function getBlockPrefix()
    {
        return 'enhavo_unit';
    }
}
