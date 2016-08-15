<?php
/**
 * Order.php
 *
 * @since 14/08/16
 * @author gseidel
 */

namespace Enhavo\Bundle\ShopBundle\Entity;

use Sylius\Component\Cart\Model\Cart;
use Enhavo\Bundle\ShopBundle\Model\OrderInterface;

class Order extends Cart implements OrderInterface
{
    /**
     * @var string
     */
    private $checkoutState;

    /**
     * @var string
     */
    private $paymentState;

    /**
     * @var string
     */
    private $shippingState;

    /**
     * @var \Sylius\Component\Promotion\Model\CouponInterface
     */
    private $promotionCoupon;

    /**
     * @var \Sylius\Component\Addressing\Model\Address
     */
    private $shippingAddress;

    /**
     * @var \Sylius\Component\Addressing\Model\Address
     */
    private $billingAddress;

    /**
     * @var boolean
     */
    private $differentBillingAddress;

    /**
     * @var \DateTime
     */
    private $orderedAt;

    /**
     * Set checkoutState
     *
     * @param string $checkoutState
     *
     * @return Order
     */
    public function setCheckoutState($checkoutState)
    {
        $this->checkoutState = $checkoutState;

        return $this;
    }

    /**
     * Get checkoutState
     *
     * @return string
     */
    public function getCheckoutState()
    {
        return $this->checkoutState;
    }

    /**
     * Set paymentState
     *
     * @param string $paymentState
     *
     * @return Order
     */
    public function setPaymentState($paymentState)
    {
        $this->paymentState = $paymentState;

        return $this;
    }

    /**
     * Get paymentState
     *
     * @return string
     */
    public function getPaymentState()
    {
        return $this->paymentState;
    }

    /**
     * Set shippingState
     *
     * @param string $shippingState
     *
     * @return Order
     */
    public function setShippingState($shippingState)
    {
        $this->shippingState = $shippingState;

        return $this;
    }

    /**
     * Get shippingState
     *
     * @return string
     */
    public function getShippingState()
    {
        return $this->shippingState;
    }

    /**
     * Set promotionCoupon
     *
     * @param \Sylius\Component\Promotion\Model\CouponInterface $promotionCoupon
     *
     * @return Order
     */
    public function setPromotionCoupon(\Sylius\Component\Promotion\Model\CouponInterface $promotionCoupon = null)
    {
        $this->promotionCoupon = $promotionCoupon;

        return $this;
    }

    /**
     * Get promotionCoupon
     *
     * @return \Sylius\Component\Promotion\Model\CouponInterface
     */
    public function getPromotionCoupon()
    {
        return $this->promotionCoupon;
    }

    /**
     * Set shippingAddress
     *
     * @param \Sylius\Component\Addressing\Model\Address $shippingAddress
     *
     * @return Order
     */
    public function setShippingAddress(\Sylius\Component\Addressing\Model\Address $shippingAddress = null)
    {
        $this->shippingAddress = $shippingAddress;

        return $this;
    }

    /**
     * Get shippingAddress
     *
     * @return \Sylius\Component\Addressing\Model\Address
     */
    public function getShippingAddress()
    {
        return $this->shippingAddress;
    }

    /**
     * Set billingAddress
     *
     * @param \Sylius\Component\Addressing\Model\Address $billingAddress
     *
     * @return Order
     */
    public function setBillingAddress(\Sylius\Component\Addressing\Model\Address $billingAddress = null)
    {
        $this->billingAddress = $billingAddress;

        return $this;
    }

    /**
     * Get billingAddress
     *
     * @return \Sylius\Component\Addressing\Model\Address
     */
    public function getBillingAddress()
    {
        return $this->billingAddress;
    }

    /**
     * Set differentBillingAddress
     *
     * @param boolean $differentBillingAddress
     * @return Order
     */
    public function setDifferentBillingAddress($differentBillingAddress)
    {
        $this->differentBillingAddress = $differentBillingAddress;

        return $this;
    }

    /**
     * Get differentBillingAddress
     *
     * @return boolean 
     */
    public function getDifferentBillingAddress()
    {
        return $this->differentBillingAddress;
    }

    /**
     * Set orderedAt
     *
     * @param \DateTime $orderedAt
     * @return Order
     */
    public function setOrderedAt($orderedAt)
    {
        $this->orderedAt = $orderedAt;

        return $this;
    }

    /**
     * Get orderedAt
     *
     * @return \DateTime 
     */
    public function getOrderedAt()
    {
        return $this->orderedAt;
    }
}
