<?php
/**
 * CurrencyType.php
 *
 * @since 09/12/16
 * @author gseidel
 */

namespace Enhavo\Bundle\FormBundle\Form\Type;

use Enhavo\Bundle\FormBundle\Form\Transformer\CurrencyTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\CallbackTransformer;

class CurrencyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->addModelTransformer(new CurrencyTransformer())
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
