<?php

namespace Enhavo\Bundle\ShopBundle\Model;

use Sylius\Component\Order\Model\OrderInterface as BaseOrderInterface;
use Sylius\Component\Addressing\Model\AddressInterface;
use Sylius\Component\Promotion\Model\CouponInterface;

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
     * @param CouponInterface $promotionCoupon
     *
     * @return OrderInterface
     */
    public function setPromotionCoupon(CouponInterface $promotionCoupon = null);

    /**
     * Get promotionCoupon
     *
     * @return CouponInterface
     */
    public function getPromotionCoupon();

    /**
     * Set shippingAddress
     *
     * @param AddressInterface $shippingAddress
     *
     * @return OrderInterface
     */
    public function setShippingAddress(AddressInterface $shippingAddress = null);

    /**
     * Get shippingAddress
     *
     * @return AddressInterface
     */
    public function getShippingAddress();

    /**
     * Set billingAddress
     *
     * @param AddressInterface $billingAddress
     *
     * @return OrderInterface
     */
    public function setBillingAddress(AddressInterface $billingAddress = null);

    /**
     * Get billingAddress
     *
     * @return AddressInterface
     */
    public function getBillingAddress();
}