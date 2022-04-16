<?php

namespace Enhavo\Bundle\ShopBundle\State;

interface OrderPaymentTransitions
{
    public const GRAPH = 'sylius_order_payment';

    public const TRANSITION_REQUEST_PAYMENT = 'request_payment';

    public const TRANSITION_PARTIALLY_AUTHORIZE = 'partially_authorize';

    public const TRANSITION_AUTHORIZE = 'authorize';

    public const TRANSITION_PARTIALLY_PAY = 'partially_pay';

    public const TRANSITION_CANCEL = 'cancel';

    public const TRANSITION_PAY = 'pay';

    public const TRANSITION_PARTIALLY_REFUND = 'partially_refund';

    public const TRANSITION_REFUND = 'refund';
}
