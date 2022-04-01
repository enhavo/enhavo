<?php
/**
 * Created by PhpStorm.
 * User: philippsester
 * Date: 21.09.20
 * Time: 17:56
 */

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\ShopBundle\Form\Type;

use Enhavo\Bundle\FormBundle\Form\Type\ListType;
use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductGenerateVariantsType extends AbstractResourceType
{
    /**
     * @param array|string[] $validationGroups
     */
    public function __construct(string $dataClass, array $validationGroups)
    {
        parent::__construct($dataClass, $validationGroups);
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('variants', ListType::class, [
                'entry_type' => ProductVariantGenerationType::class,
                'allow_add' => false,
                'allow_delete' => true,
                'by_reference' => false,
                'border' => true,
                'sortable' => true,
                'sortable_property' => 'position'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(array(
            'validation_groups' => ['product-variant']
        ));
    }

    public function getParent()
    {
        return \Sylius\Bundle\ProductBundle\Form\Type\ProductGenerateVariantsType::class;
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix(): string
    {
        return 'enhavo_product_generate_variants';
    }
}
