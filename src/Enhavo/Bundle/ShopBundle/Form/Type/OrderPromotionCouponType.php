<?php
/**
 * OrderPromotionCoupon.php
 *
 * @since 19/08/16
 * @author gseidel
 */

namespace Enhavo\Bundle\ShopBundle\Form\Type;

use Sylius\Bundle\PromotionBundle\Form\Type\PromotionCouponToCodeType;
use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Symfony\Component\Form\FormBuilderInterface;

class OrderPromotionCouponType extends AbstractResourceType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('promotionCoupon', PromotionCouponToCodeType::class, [
            'label' => 'promotion.coupon.form.label.code',
            'translation_domain' => 'EnhavoShopBundle',
        ]);
    }
}
