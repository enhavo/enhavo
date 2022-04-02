<?php

namespace Enhavo\Bundle\ShopBundle\Model;

use Sylius\Component\Order\Model\OrderItemInterface as SyliusOrderItemInterface;

interface OrderItemInterface extends SyliusOrderItemInterface
{
    public function setProduct(ProductAccessInterface $product);

    /**
     * @param string $name
     *
     */
    public function setName($name);

    /**
     * @return  string $name
     *
     */
    public function getName();

    /**
     * @return ProductInterface
     */
    public function getProduct();

    /**
     * Return the total amount of all taxes including the units
     *
     * @return integer
     */
    public function getTaxTotal();

    /**
     * Returns the total amount of a single unit including taxes and promotion
     *
     * @return integer
     */
    public function getUnitTotal();

    /**
     * Returns the amount of tax for a single unit
     *
     * @return integer
     */
    public function getUnitTax();

    /**
     * Returns the summary amount of all unit prices
     *
     * @return integer
     */
    public function getUnitPriceTotal();
}
