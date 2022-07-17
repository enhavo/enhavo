<?php

namespace Enhavo\Bundle\ShopBundle\Model;

interface PriceAccessInterface
{
    public function getGrossPrice(): ?int;
    public function getNetPrice(): ?int;
    public function getTaxPrice(): ?int;
}
