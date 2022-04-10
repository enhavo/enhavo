<?php

namespace Enhavo\Bundle\ShopBundle\Manager;

use Enhavo\Bundle\ShopBundle\Model\OrderInterface;
use Enhavo\Bundle\ShopBundle\Taxation\Applicator\OrderTaxesApplicatorInterface;
use Laminas\Stdlib\PriorityQueue;

class TaxationManager
{
    /** @var PriorityQueue<OrderTaxesApplicatorInterface> */
    private PriorityQueue $applicator;

    public function __construct()
    {
        $this->applicator = new PriorityQueue();
    }

    public function addApplicator(OrderTaxesApplicatorInterface $applicator, int $priority = 1)
    {
        $this->applicator->insert($applicator, $priority);
    }

    public function applyTaxes(OrderInterface $order)
    {
        foreach ($this->applicator as $applicator) {
            $applicator->apply($order);
        }
    }
}
