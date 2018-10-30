<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 29.10.18
 * Time: 21:34
 */

namespace Enhavo\Bundle\ShopBundle\Entity;

use Sylius\Component\Promotion\Model\Coupon;
use Sylius\Component\Resource\Model\ResourceInterface;

class Voucher implements ResourceInterface
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $code;

    /**
     * @var integer
     */
    private $amount;

    /**
     * @var integer
     */
    private $redeemAmount = 0;

    /**
     * @var \DateTime
     */
    private $redeemedAt;

    /**
     * @var \DateTime
     */
    private $createdAt;

    /**
     * @var Coupon
     */
    private $coupon;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param string $code
     */
    public function setCode($code)
    {
        $this->code = $code;
        $this->coupon->setCode($code);
    }

    /**
     * @return int
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param int $amount
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime $createdAt
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return \DateTime
     */
    public function getRedeemedAt()
    {
        return $this->redeemedAt;
    }

    /**
     * @param \DateTime $redeemedAt
     */
    public function setRedeemedAt($redeemedAt)
    {
        $this->redeemedAt = $redeemedAt;
    }

    /**
     * @return Coupon
     */
    public function getCoupon()
    {
        return $this->coupon;
    }

    /**
     * @param Coupon $coupon
     */
    public function setCoupon($coupon)
    {
        $this->coupon = $coupon;
    }

    /**
     * @return int
     */
    public function getRedeemAmount()
    {
        return $this->redeemAmount;
    }

    /**
     * @param int $redeemAmount
     */
    public function setRedeemAmount($redeemAmount)
    {
        $this->redeemAmount = $redeemAmount;
    }

    public function getAvailableAmount()
    {
        return intval($this->amount) - intval($this->redeemAmount);
    }

    public function addRedeemAmount($amount)
    {
        $this->redeemAmount = intval($this->redeemAmount) + intval($amount);
        if($this->getAvailableAmount() <= 0) {
            $this->getCoupon()->setExpiresAt(new \DateTime());
        }
    }
}