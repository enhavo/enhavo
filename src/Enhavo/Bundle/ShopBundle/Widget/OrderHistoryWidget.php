<?php
/**
 * OrderHistoryWidget.php
 *
 * @since 12/03/16
 * @author gseidel
 */

namespace Enhavo\Bundle\ShopBundle\Widget;

use Enhavo\Bundle\AppBundle\Type\AbstractType;
use Enhavo\Bundle\ThemeBundle\Widget\WidgetInterface;

class OrderHistoryWidget extends AbstractType implements WidgetInterface
{
    public function getType()
    {
        return 'shop_order_history';
    }

    public function render($options)
    {
        $orders = $this->getOrderProvider()->getOrders($this->getUser());

        $resolvedOptions = $this->resolveOptions([
            'template' => 'EnhavoShopBundle:Theme:Widget/order-history.html.twig',
            'orders' => $orders,
        ], $options);

        return $this->renderTemplate($resolvedOptions['template'], [
            'orders' => $resolvedOptions['orders']
        ]);
    }

    private function getOrderProvider()
    {
        return $this->container->get('enhavo_shop.order.order_provider');
    }

    private function getUser()
    {
        return $this->container->get('security.token_storage')->getToken()->getUser();
    }
}