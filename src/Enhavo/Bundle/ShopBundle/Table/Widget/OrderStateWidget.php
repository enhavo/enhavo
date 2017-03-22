<?php
/**
 * Created by PhpStorm.
 * User: jhelbing
 * Date: 02.02.16
 * Time: 11:10
 */

namespace Enhavo\Bundle\ShopBundle\Table\Widget;;

use Enhavo\Bundle\AppBundle\Table\AbstractTableWidget;
use Enhavo\Bundle\ShopBundle\Entity\Order;

class OrderStateWidget extends AbstractTableWidget
{
    const STATE_SHIPPED = 'shipped';
    const STATE_PAYED = 'payed';
    const STATE_CANCELLED = 'cancelled';
    const STATE_PACKED = 'packed';

    public function render($options, $item)
    {
        if($item instanceof Order) {
            $property = $options['property'];
            $value = null;
            if($property == self::STATE_SHIPPED) {
                $value = $item->getShippingState() == 'shipped';
            }

            if($property == self::STATE_PAYED) {
                $value = $item->getPaymentState() == 'completed';
            }

            if($property == self::STATE_PACKED) {
                $value = $item->getShippingState() == 'ready' || $item->getShippingState() == 'shipped';
            }

            if($property == self::STATE_CANCELLED) {
                $value = $item->getState() == 'cancelled';
            }

            return $this->renderTemplate('EnhavoAppBundle:TableWidget:boolean.html.twig', array(
                'value' => $value
            ));
        }
        return '';
    }

    public function getType()
    {
        return 'order_state';
    }
}