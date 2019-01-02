<?php
/**
 * ProductVariantType.php
 *
 * @since 23/10/16
 * @author gseidel
 */

namespace Enhavo\Bundle\ShopBundle\Form\Type;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class ProductVariantType extends AbstractType
{
    /**
     * @var string
     */
    protected $dataClass;

    /**
     * ProductVariantCreateType constructor.
     * @param $dataClass
     */
    public function __construct($dataClass)
    {
        $this->dataClass = $dataClass;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('code', TextType::class);
    }

    public function getBlockPrefix()
    {
        return 'enhavo_shop_product_variant';
    }
}