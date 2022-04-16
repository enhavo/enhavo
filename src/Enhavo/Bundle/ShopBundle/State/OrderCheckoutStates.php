<?php

namespace Enhavo\Bundle\ShopBundle\State;

interface OrderCheckoutStates
{
    public const STATE_ADDRESSED = 'addressed';

    public const STATE_CART = 'cart';

    public const STATE_COMPLETED = 'completed';

    public const STATE_PAYMENT_SELECTED = 'payment_selected';

    public const STATE_PAYMENT_SKIPPED = 'payment_skipped';

    public const STATE_SHIPPING_SELECTED = 'shipping_selected';

    public const STATE_SHIPPING_SKIPPED = 'shipping_skipped';
}
