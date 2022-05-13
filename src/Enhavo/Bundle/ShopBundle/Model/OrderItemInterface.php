<?php

namespace Enhavo\Bundle\ShopBundle\Model;

use Sylius\Component\Order\Model\OrderItemInterface as SyliusOrderItemInterface;

interface OrderItemInterface extends SyliusOrderItemInterface
{
    public function setProduct(ProductAccessInterface $product);

    public function getProduct(): ?ProductAccessInterface;

    public function setName(?string $name): void;

    public function getName(): ?string;

    public function setConfiguration(array $configuration);

    public function getConfiguration(): array;

    public function getTaxTotal(): int;

    public function getUnitTotal(): int;

    public function getUnitTax(): int;

    public function getUnitPriceTotal(): int;


}
