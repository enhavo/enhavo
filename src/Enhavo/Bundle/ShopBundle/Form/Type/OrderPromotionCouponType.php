<?php
/**
 * OrderPromotionCoupon.php
 *
 * @since 19/08/16
 * @author gseidel
 */

namespace Enhavo\Bundle\ShopBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotNull;

class OrderPromotionCouponType extends AbstractType
{
    /**
     * @var string
     */
    private $dataClass;

    public function __construct($dataClass)
    {
        $this->dataClass = $dataClass;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('promotionCoupon', 'sylius_promotion_coupon_to_code', [
            'label' => 'promotion.coupon.form.label.code',
            'translation_domain' => 'EnhavoShopBundle',
            'required' => $options['required']
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => $this->dataClass,
            'validation_groups' => ['redeem'],
            'required' => true
        ));
    }

    public function getBlockPrefix()
    {
        return 'enhavo_shop_order_promotion_coupon';
    }
}