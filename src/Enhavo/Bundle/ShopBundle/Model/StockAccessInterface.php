<?php

namespace Enhavo\Bundle\ShopBundle\Model;

interface StockAccessInterface
{
    public function getStock(): ?int;
    public function setStock(?int $stock): void;
    public function isStockTracked(): bool;
    public function setStockTracked(bool $stockTracked): void;
}
