<?php

namespace Enhavo\Bundle\ShopBundle\Entity;

use Enhavo\Bundle\ShopBundle\Model\ProductAccessInterface;
use Enhavo\Bundle\ShopBundle\Model\ProductVariantInterface;
use Enhavo\Bundle\ShopBundle\Model\ProductVariantProxyInterface;
use Sylius\Component\Order\Model\OrderItem as SyliusOrderItem;
use Enhavo\Bundle\ShopBundle\Model\AdjustmentInterface;
use Sylius\Component\Order\Model\OrderItemInterface as SyliusOrderItemInterface;
use Enhavo\Bundle\ShopBundle\Model\OrderItemInterface;
use Sylius\Component\Resource\Model\ResourceInterface;

class OrderItem extends SyliusOrderItem implements OrderItemInterface
{
    private ?ProductAccessInterface $product;
    private ?string $name;
    private array $configuration = [];
    private bool $shippable = true;

    public function getProduct(): ?ProductAccessInterface
    {
        return $this->product;
    }

    public function setProduct(?ProductAccessInterface $product)
    {
        $this->product = $product;
    }

    public function getConfiguration(): array
    {
        return $this->configuration;
    }

    public function setConfiguration(array $configuration): void
    {
        $this->configuration = $configuration;
    }

    public function equals(SyliusOrderItemInterface $item): bool
    {
        if (!$item instanceof OrderItemInterface) {
            return parent::equals($item);
        }

        if ($this->product instanceof ProductVariantProxyInterface) {
            if ($item->getProduct() instanceof ProductVariantProxyInterface) {
                return $this->product->getProductVariant() === $item->getProduct()->getProductVariant();
            } elseif ($item instanceof ProductVariantInterface) {
                return $this->product->getProductVariant() === $item->getProduct();
            }
        } elseif ($this->product instanceof ResourceInterface) {
            return $this->product === $item->getProduct() || $this->product->getId() == $item->getProduct()->getId();
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function getUnitPriceTotal(): int
    {
        return $this->unitPrice * $this->quantity;
    }

    /**
     * {@inheritdoc}
     */
    public function getTaxTotal(): int
    {
        $total = 0;
        foreach($this->getUnits() as $unit) {
            $adjustments = $unit->getAdjustments(AdjustmentInterface::TAX_ADJUSTMENT);
            foreach($adjustments as $adjustment) {
                $total += $adjustment->getAmount();
            }

            $adjustments = $unit->getAdjustments(AdjustmentInterface::TAX_PROMOTION_ADJUSTMENT);
            foreach($adjustments as $adjustment) {
                $total += $adjustment->getAmount();
            }
        }

        return $total;
    }

    /**
     * {@inheritdoc}
     */
    public function getUnitTotal(): int
    {
        return $this->getUnitPrice() + $this->getUnitTax() + $this->getUnitDiscount();
    }

    /**
     * {@inheritdoc}
     */
    public function getUnitTax(): int
    {
        $total = 0;
        $unit = $this->getUnits()->first();

        $adjustments = $unit->getAdjustments(AdjustmentInterface::TAX_ADJUSTMENT);
        /** @var AdjustmentInterface $adjustment */
        foreach($adjustments as $adjustment) {
            $total += $adjustment->getAmount();
        }

        $adjustments = $unit->getAdjustments(AdjustmentInterface::TAX_PROMOTION_ADJUSTMENT);
        /** @var AdjustmentInterface $adjustment */
        foreach($adjustments as $adjustment) {
            $total += $adjustment->getAmount();
        }

        return $total;
    }

    public function getUnitDiscount()
    {
        $total = 0;
        $unit = $this->getUnits()->first();
        $adjustments = $unit->getAdjustments(AdjustmentInterface::ORDER_UNIT_PROMOTION_ADJUSTMENT);
        /** @var AdjustmentInterface $adjustment */
        foreach($adjustments as $adjustment) {
            $total += $adjustment->getAmount();
        }
        return $total;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    public function isShippable(): bool
    {
        return $this->shippable;
    }

    public function setShippable(bool $shippable): void
    {
        $this->shippable = $shippable;
    }
}
