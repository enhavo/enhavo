<?php
/**
 * OrderComposition.php
 *
 * @since 28/08/16
 * @author gseidel
 */

namespace Enhavo\Bundle\ShopBundle\Model;


class OrderItemComposition
{
    /**
     * @var int
     */
    private $unitPrice = 0;

    /**
     * @var int
     */
    private $unitTax = 0;

    /**
     * @var int
     */
    private $unitTotal = 0;

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
     * @return int
     */
    public function getUnitPrice()
    {
        return $this->unitPrice;
    }

    /**
     * @param int $unitPrice
     */
    public function setUnitPrice($unitPrice)
    {
        $this->unitPrice = $unitPrice;
    }

    /**
     * @return int
     */
    public function getUnitTax()
    {
        return $this->unitTax;
    }

    /**
     * @param int $unitTax
     */
    public function setUnitTax($unitTax)
    {
        $this->unitTax = $unitTax;
    }

    /**
     * @return int
     */
    public function getUnitTotal()
    {
        return $this->unitTotal;
    }

    /**
     * @param int $unitTotal
     */
    public function setUnitTotal($unitTotal)
    {
        $this->unitTotal = $unitTotal;
    }

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

    public function toArray()
    {
        return [
            'unitPrice' => $this->unitPrice,
            'unitTax' => $this->unitTax,
            'unitTotal' => $this->unitTotal,
            'total' => $this->total,
            'taxTotal' => $this->taxTotal,
            'netTotal' => $this->netTotal
        ];
    }
}