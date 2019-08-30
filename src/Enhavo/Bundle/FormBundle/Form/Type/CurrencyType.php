<?php
/**
 * CurrencyType.php
 *
 * @since 09/12/16
 * @author gseidel
 */

namespace Enhavo\Bundle\FormBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\CallbackTransformer;

class CurrencyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->addModelTransformer(new CallbackTransformer(
                function ($currencyAsInt) {
                    //int -> text
                    $string = (string)$currencyAsInt;
                    $string = str_pad($string, 3, '0', STR_PAD_LEFT);
                    $length = strlen($string);
                    $right = substr($string, $length - 2, 2 );
                    $left = substr($string, 0, $length - 2);
                    $value = $left.','.$right;
                    return $value;
                },
                function ($currencyAsString) {
                    //text -> int
                    $string = $currencyAsString;
                    $string = str_replace('.', '', $string);

                    $parts = explode(',', $string);
                    $right = 0;
                    if(count($parts) > 1) {
                        $right = array_pop($parts);
                        $right = substr($right, 0, 2);
                        $right = str_pad($right, 2, '0');
                        $right = intval($right);
                    }

                    $left = implode($parts);
                    $left = intval($left);

                    $value = $right;
                    if($left > 0) {
                        $value = $left*100 + $value;
                    }

                    return $value;
                }
            ))
        ;
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
