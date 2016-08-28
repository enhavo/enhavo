<?php
/**
 * OrderComposition.php
 *
 * @since 28/08/16
 * @author gseidel
 */

namespace Enhavo\Bundle\ShopBundle\Model;


class OrderComposition
{
    /**
     * @var int
     */
    private $total = 0;

    /**
     * @var int
     */
    private $taxTotal = 0;

    /**
     * @var int
     */
    private $netTotal = 0;

    /**
     * @var int
     */
    private $shippingTotal = 0;

    /**
     * @var int
     */
    private $discountTotal = 0;

    /**
     * @return int
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * @param int $total
     */
    public function setTotal($total)
    {
        $this->total = $total;
    }

    /**
     * @return int
     */
    public function getTaxTotal()
    {
        return $this->taxTotal;
    }

    /**
     * @param int $taxTotal
     */
    public function setTaxTotal($taxTotal)
    {
        $this->taxTotal = $taxTotal;
    }

    /**
     * @return int
     */
    public function getNetTotal()
    {
        return $this->netTotal;
    }

    /**
     * @param int $netTotal
     */
    public function setNetTotal($netTotal)
    {
        $this->netTotal = $netTotal;
    }

    /**
     * @return int
     */
    public function getShippingTotal()
    {
        return $this->shippingTotal;
    }

    /**
     * @param int $shippingTotal
     */
    public function setShippingTotal($shippingTotal)
    {
        $this->shippingTotal = $shippingTotal;
    }

    /**
     * @return int
     */
    public function getDiscountTotal()
    {
        return $this->discountTotal;
    }

    /**
     * @param int $discountTotal
     */
    public function setDiscountTotal($discountTotal)
    {
        $this->discountTotal = $discountTotal;
    }

    public function toArray()
    {
        return [
            'total' => $this->total,
            'taxTotal' => $this->taxTotal,
            'netTotal' => $this->netTotal,
            'shippingTotal' => $this->shippingTotal,
            'discountTotal' => $this->discountTotal
        ];
    }
}