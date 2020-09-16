<?php
/**
 * Created by PhpStorm.
 * User: philippsester
 * Date: 01.09.20
 * Time: 11:57
 */

namespace Enhavo\Bundle\ShopBundle\Form\Type;

use Sylius\Bundle\AttributeBundle\Form\Type\AttributeTypeChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Sylius\Bundle\ProductBundle\Form\Type\ProductAttributeType as SyliusProductAttributeType;

class ProductAttributeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('position', IntegerType::class, [
                'label' =>  'product_attribute.label.position',
                'translation_domain' => 'EnhavoShopBundle',
            ])
            ->add('storage_type', TextType::class, [
                 'label' =>  'product_attribute.label.storage_type',
                 'translation_domain' => 'EnhavoShopBundle',
            ])
        ;
    }

    public function getParent()
    {
        return SyliusProductAttributeType::class;
    }
}
