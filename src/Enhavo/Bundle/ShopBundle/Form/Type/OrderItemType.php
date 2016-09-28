<?php
/**
 * OrderItemType.php
 *
 * @since 27/09/16
 * @author gseidel
 */

namespace Enhavo\Bundle\ShopBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\DataMapperInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderItemType extends AbstractType
{
    /**
     * @var DataMapperInterface
     */
    protected $orderItemQuantityDataMapper;

    /**
     * @var string
     */
    protected $dataClass;

    /**
     * @var string
     */
    protected $productClass;

    /**
     * @param string $dataClass
     * @param DataMapperInterface $orderItemQuantityDataMapper
     */
    public function __construct($dataClass, DataMapperInterface $orderItemQuantityDataMapper, $productClass)
    {
        $this->orderItemQuantityDataMapper = $orderItemQuantityDataMapper;
        $this->dataClass = $dataClass;
        $this->productClass = $productClass;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('quantity', 'text', [
                'label' => 'order_item.form.label.quantity',
                'translation_domain' => 'EnhavoShopBundle'
            ])
            ->add('product', 'entity', [
                'class' => $this->productClass,
                'choice_name' => 'title',
                'label' => 'order_item.form.label.product',
                'translation_domain' => 'EnhavoShopBundle'
            ])
            ->setDataMapper($this->orderItemQuantityDataMapper)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => $this->dataClass,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'enhavo_shop_order_item';
    }
}

