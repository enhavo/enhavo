<?php

namespace Enhavo\Bundle\ShopBundle\Model;

use Sylius\Component\Order\Model\OrderInterface as BaseOrderInterface;

/**
 * OrderInterface.php
 *
 * @since 15/08/16
 * @author gseidel
 */
interface OrderInterface extends BaseOrderInterface
{
    /**
     * Set checkoutState
     *
     * @param string $checkoutState
     *
     * @return OrderInterface
     */
    public function setCheckoutState($checkoutState);

    /**
     * Get checkoutState
     *
     * @return string
     */
    public function getCheckoutState();

    /**
     * Set paymentState
     *
     * @param string $paymentState
     *
     * @return OrderInterface
     */
    public function setPaymentState($paymentState);

    /**
     * Get paymentState
     *
     * @return string
     */
    public function getPaymentState();

    /**
     * Set shippingState
     *
     * @param string $shippingState
     *
     * @return OrderInterface
     */
    public function setShippingState($shippingState);

    /**
     * Get shippingState
     *
     * @return string
     */
    public function getShippingState();

    /**
     * Set promotionCoupon
     *
     * @param \Sylius\Component\Promotion\Model\CouponInterface $promotionCoupon
     *
     * @return OrderInterface
     */
    public function setPromotionCoupon(\Sylius\Component\Promotion\Model\CouponInterface $promotionCoupon = null);

    /**
     * Get promotionCoupon
     *
     * @return \Sylius\Component\Promotion\Model\CouponInterface
     */
    public function getPromotionCoupon();

    /**
     * Set shippingAddress
     *
     * @param \Sylius\Component\Addressing\Model\Address $shippingAddress
     *
     * @return OrderInterface
     */
    public function setShippingAddress(\Sylius\Component\Addressing\Model\Address $shippingAddress = null);

    /**
     * Get shippingAddress
     *
     * @return \Sylius\Component\Addressing\Model\Address
     */
    public function getShippingAddress();

    /**
     * Set billingAddress
     *
     * @param \Sylius\Component\Addressing\Model\Address $billingAddress
     *
     * @return OrderInterface
     */
    public function setBillingAddress(\Sylius\Component\Addressing\Model\Address $billingAddress = null);

    /**
     * Get billingAddress
     *
     * @return \Sylius\Component\Addressing\Model\Address
     */
    public function getBillingAddress();
}