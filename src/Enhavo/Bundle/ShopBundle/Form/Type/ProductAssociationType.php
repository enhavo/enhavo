<?php
/**
 * Created by PhpStorm.
 * User: philippsester
 * Date: 28.08.20
 * Time: 17:37
 */


namespace Enhavo\Bundle\ShopBundle\Form\Type;

use Enhavo\Bundle\FormBundle\Form\Type\AutoCompleteEntityType;
use Enhavo\Bundle\ShopBundle\Entity\Product;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Sylius\Bundle\ProductBundle\Form\Type\ProductAssociationType as SyliusProductAssociationType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductAssociationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $productId = !empty($options['productId']) ? $options['productId'] : 0;
        $builder->remove('product');
        $builder->add('associatedProducts', AutoCompleteEntityType::class, [
            'label' => 'product.label.product',
            'translation_domain' => 'EnhavoShopBundle',
            'multiple' => true,
            'class' => Product::class,
            'route' => "sylius_product_auto_complete",
            'route_parameters' => [
                'id' => $productId
            ]
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired(['productId']);
    }

    public function getParent()
    {
        return SyliusProductAssociationType::class;
    }
}
