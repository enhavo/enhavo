<?php
/**
 * ProductListWidget.php
 *
 * @since 25/09/16
 * @author gseidel
 */

namespace Enhavo\Bundle\ShopBundle\Widget;

use Enhavo\Bundle\ThemeBundle\Widget\WidgetInterface;
use Enhavo\Bundle\AppBundle\Type\AbstractType;
use Symfony\Component\Form\Form;

class CouponRedeemWidget extends AbstractType implements WidgetInterface
{
    /**
     * @inheritDoc
     */
    public function getType()
    {
        return 'shop_coupon_redeem';
    }

    public function render($options)
    {
        $template = $this->getOption('template', $options, 'EnhavoShopBundle:Theme:Widget/coupon-redeem.html.twig');
        $formType = $this->getOption('form', $options, 'enhavo_shop_order_promotion_coupon');

        if(!array_key_exists('order', $options)) {
            $cart = $this->container->get('sylius.cart_provider')->getCart();
            $options['order'] = $cart;
        }

        $formFactory = $this->container->get('form.factory');
        $form = $formFactory->create($formType , $options['order']);

        return $this->renderTemplate($template, [
            'order' => $options['order'],
            'form' => $form->createView()
        ]);
    }
}