<?php
/**
 * Created by PhpStorm.
 * User: jhelbing
 * Date: 02.02.16
 * Time: 11:10
 */

namespace Enhavo\Bundle\ShopBundle\Table\Widget;;

use Enhavo\Bundle\AppBundle\Column\AbstractColumnType;
use Enhavo\Bundle\ShopBundle\Entity\Order;

class OrderStateWidget extends AbstractColumnType
{
    const STATE_SHIPPED = 'shipped';
    const STATE_PAYED = 'payed';
    const STATE_CANCELLED = 'cancelled';
    const STATE_PACKED = 'packed';

    public function createResourceViewData(array $options, $resource)
    {
        if($resource instanceof Order) {
            $property = $options['property'];
            $value = null;
            if($property == self::STATE_SHIPPED) {
                $value = $resource->getShippingState() == 'shipped';
            }

            if($property == self::STATE_PAYED) {
                $value = $resource->getPaymentState() == 'completed';
            }

            if($property == self::STATE_PACKED) {
                $value = $resource->getShippingState() == 'ready' || $resource->getShippingState() == 'shipped';
            }

            if($property == self::STATE_CANCELLED) {
                $value = $resource->getState() == 'cancelled';
            }

            return $value;
        }
        return '';
    }

    public function getType()
    {
        return 'order_state';
    }
}
