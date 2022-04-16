<?php

namespace Enhavo\Bundle\ShopBundle\State;

interface OrderShippingStates
{
    public const STATE_CART = 'cart';

    public const STATE_READY = 'ready';

    public const STATE_CANCELLED = 'cancelled';

    public const STATE_PARTIALLY_SHIPPED = 'partially_shipped';

    public const STATE_SHIPPED = 'shipped';
}
