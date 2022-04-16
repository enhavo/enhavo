<?php

namespace Enhavo\Bundle\ShopBundle\State;

interface OrderCheckoutTransitions
{
    public const GRAPH = 'sylius_order_checkout';

    public const TRANSITION_ADDRESS = 'address';

    public const TRANSITION_COMPLETE = 'complete';

    public const TRANSITION_SELECT_PAYMENT = 'select_payment';

    public const TRANSITION_SELECT_SHIPPING = 'select_shipping';

    public const TRANSITION_SKIP_PAYMENT = 'skip_payment';

    public const TRANSITION_SKIP_SHIPPING = 'skip_shipping';
}
