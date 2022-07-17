<?php

namespace Enhavo\Bundle\ShopBundle\Manager;

use Enhavo\Bundle\ShopBundle\Model\ProductAccessInterface;
use Enhavo\Bundle\ShopBundle\Pricing\PriceCalculatorInterface;
use Laminas\Stdlib\PriorityQueue;

class PricingManager
{
    /** @var PriorityQueue<PriceCalculatorInterface>|PriceCalculatorInterface[] */
    private PriorityQueue $calculators;

    public function __construct()
    {
        $this->calculators = new PriorityQueue();
    }

    public function addCalculator(PriceCalculatorInterface $calculator, int $priority = 1)
    {
        $this->calculators->insert($calculator, $priority);
    }

    public function calculatePrice(ProductAccessInterface $product, array $configuration = [])
    {
        foreach ($this->calculators as $calculator) {
            if ($calculator->isSupported($product)) {
                return $calculator->calculate($product, $configuration);
            }
        }
    }

    public function calculateTax(ProductAccessInterface $product, array $configuration = [])
    {
        foreach ($this->calculators as $calculator) {
            if ($calculator->isSupported($product)) {
                return $calculator->calculateTax($product, $configuration);
            }
        }
    }
}
