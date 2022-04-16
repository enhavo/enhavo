<?php

namespace Enhavo\Bundle\ShopBundle\State;

interface OrderShippingTransitions
{
    public const GRAPH = 'sylius_order_shipping';

    public const TRANSITION_REQUEST_SHIPPING = 'request_shipping';

    public const TRANSITION_PARTIALLY_SHIP = 'partially_ship';

    public const TRANSITION_SHIP = 'ship';

    public const TRANSITION_CANCEL = 'cancel';
}
