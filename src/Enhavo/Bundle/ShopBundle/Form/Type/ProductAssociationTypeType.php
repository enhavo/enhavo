<?php
/**
 * Created by PhpStorm.
 * User: philippsester
 * Date: 28.08.20
 * Time: 17:37
 */


namespace Enhavo\Bundle\ShopBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Sylius\Bundle\ProductBundle\Form\Type\ProductAssociationTypeType as SyliusProductAssociationTypeType;

class ProductAssociationTypeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
//        $builder->remove('translations');
//        $builder
//            ->add('position', IntegerType::class, [
//                'label' =>  'product_attribute.label.position',
//                'translation_domain' => 'EnhavoShopBundle',
//            ])
//        ;
    }

    public function getParent()
    {
        return SyliusProductAssociationTypeType::class;
    }
}
