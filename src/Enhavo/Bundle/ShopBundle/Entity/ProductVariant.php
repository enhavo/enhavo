<?php
/**
 * Created by PhpStorm.
 * User: philippsester
 * Date: 26.08.20
 * Time: 16:32
 */

namespace Enhavo\Bundle\ShopBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Enhavo\Bundle\ShopBundle\Model\ProductAccessTrait;
use Enhavo\Bundle\ShopBundle\Model\ProductVariantInterface;
use Enhavo\Bundle\ShopBundle\Model\StockAccessInterface;
use Sylius\Component\Product\Model\ProductVariant as SyliusProductVariant;

class ProductVariant extends SyliusProductVariant implements ProductVariantInterface, StockAccessInterface
{
    use ProductAccessTrait;

    private ?int $stock;
    private bool $stockTracked = false;
    private ?bool $default;
    private ?string $shortTitle = '';
    private bool $index = false;

    public function __construct()
    {
        parent::__construct();
        $this->position = 0;
        $this->pictures = new ArrayCollection();
    }

    public function getName(): ?string
    {
        return $this->getTitle();
    }

    public function setName(?string $name): void
    {
        $this->setTitle($name);
    }

    public function getStock(): ?int
    {
        return $this->stock;
    }

    public function setStock(?int $stock): void
    {
        $this->stock = $stock;
    }

    public function isStockTracked(): bool
    {
        return $this->stockTracked;
    }

    public function setStockTracked(bool $stockTracked): void
    {
        $this->stockTracked = $stockTracked;
    }

    public function getDefault(): ?bool
    {
        return $this->default;
    }

    public function setDefault(?bool $default): void
    {
        $this->default = $default;
    }

    public function getShortTitle(): ?string
    {
        return $this->shortTitle;
    }

    public function setShortTitle(?string $shortTitle): void
    {
        $this->shortTitle = $shortTitle;
    }

    public function isIndex(): bool
    {
        return $this->index;
    }

    public function setIndex(bool $index): void
    {
        $this->index = $index;
    }
}
