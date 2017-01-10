<?php
/**
 * Created by PhpStorm.
 * User: jenny
 * Date: 10.01.17
 * Time: 14:41
 */

namespace Enhavo\Bundle\ShopBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UnitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->addModelTransformer(new CallbackTransformer(
                function ($currencyAsInt) {
                    //int -> text
                    $string = (string)$currencyAsInt;
                    $string = str_replace('.', ',', $string);
                    return $string;
                },
                function ($currencyAsString) {
                    //text -> int
                    $string = $currencyAsString;
                    $string = str_replace(',', '.', $string);

                    return $string;
                }
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {

    }

    public function getParent()
    {
        return 'text';
    }

    public function getName()
    {
        return 'enhavo_unit';
    }
}