<?php
/**
 * Created by PhpStorm.
 * User: philippsester
 * Date: 22.09.20
 * Time: 20:17
 */

namespace Enhavo\Bundle\ShopBundle\Form\Type;

use Enhavo\Bundle\FormBundle\Form\Type\BooleanType;
use Enhavo\Bundle\FormBundle\Form\Type\PositionType;
use Sylius\Bundle\ProductBundle\Form\EventSubscriber\BuildProductVariantFormSubscriber;
use Sylius\Bundle\ResourceBundle\Form\EventSubscriber\AddCodeFormSubscriber;
use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Valid;

class ProductVariantGenerationType extends AbstractResourceType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'required' => false,
                'label' => 'product_variant.form.label.name',
                'translation_domain' => 'EnhavoShopBundle'
            ])
            ->add('stock', TextType::class, [
                'required' => true,
                'label' => 'product_variant.form.label.stock',
                'translation_domain' => 'EnhavoShopBundle'
            ])
            ->add('stockTracked', BooleanType::class, [
                'required' => true,
                'label' => 'product_variant.form.label.stock_tracked',
                'translation_domain' => 'EnhavoShopBundle'
            ])
            ->add('price', IntegerType::class, [
                'label' => 'product_variant.form.label.price',
                'translation_domain' => 'EnhavoShopBundle'
            ])
            ->add('position', PositionType::class)
            ->addEventSubscriber(new AddCodeFormSubscriber())
        ;

        $builder->addEventSubscriber(new BuildProductVariantFormSubscriber($builder->getFormFactory(), true));
    }

    /**x
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver->setDefault('constraints', [new Valid()]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix(): string
    {
        return 'sylius_product_variant_generation';
    }
}
