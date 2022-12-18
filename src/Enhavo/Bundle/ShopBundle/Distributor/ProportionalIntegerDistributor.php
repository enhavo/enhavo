<?php

namespace Enhavo\Bundle\ShopBundle\Distributor;

use Webmozart\Assert\Assert;

class ProportionalIntegerDistributor
{
    public function distribute(array $integers, int $amount): array
    {
        Assert::allInteger($integers);

        $total = array_sum($integers);
        $distributedAmounts = [];

        foreach ($integers as $element) {
            $distributedAmounts[] = (int) round(($element * $amount) / $total, 0, \PHP_ROUND_HALF_DOWN);
        }

        $missingAmount = $amount - array_sum($distributedAmounts);
        for ($i = 0, $iMax = abs($missingAmount); $i < $iMax; ++$i) {
            $distributedAmounts[$i] += $missingAmount >= 0 ? 1 : -1;
        }

        return $distributedAmounts;
    }
}
